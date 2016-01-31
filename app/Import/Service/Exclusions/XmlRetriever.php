<?php namespace App\Import\Service\Exclusions;

use GuzzleHttp\Client;
use App\Import\Lists\ExclusionList;

/**
 * Class XmlRetriever
 */
class XmlRetriever extends Retriever
{
    private function prepareItem($item)
    {
        $item = (string) $item;
        $item = trim($item);

        return $item;
    }


    /**
     * Retrieve the data and fill the passed object's data property
     *
     * @param  \App\Import\Lists\ExclusionList $list
     * @return \App\Import\Lists\ExclusionList
     */
    public function retrieveData(ExclusionList $list)
    {
        $client = new Client([
            'base_uri' => $list->uri
        ]);

        $response = $client->request('GET', $list->urlSuffix, $list->requestOptions);

        $body = $response->getBody()->getContents();

        $xml = simplexml_load_string($body);

        foreach ($xml->{$list->nodes['title']}->{$list->nodes['subject']} as $node) {
            $linesItem = [];
            foreach ($list->nodeMap as $line) {
                $linesItem[] = (is_array($line)) ? $list->$line[0]($node) : $this->prepareItem($node->{$line});
            }

            $list->data[] = $linesItem;
        }

        if ($list->retrieveOptions['headerRow'] == 1) {
            array_shift( $list->data);
        }

        if (count($list->dateColumns) > 0) {
            $list->data = $this->convertDatesToMysql($list->data, $list->dateColumns);
        }

        return $list;
    }
}
