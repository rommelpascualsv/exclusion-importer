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
        $datas = [];

        // Implement multiple file upload use comma searated
        $uri = $this->splitURI($list->uri);

        foreach ($uri as $key => $value) {
            
            $xmlContent = $this->getContentFrom($value, $list);

            $xml = simplexml_load_string($xmlContent);
            
            $data = [];
            foreach ($xml->{$list->nodes['title']}->{$list->nodes['subject']} as $node) {
                $linesItem = [];
                foreach ($list->nodeMap as $line) {
                    $linesItem[] = (is_array($line)) ? $list->$line[0]($node) : $this->prepareItem($node->{$line});
                }

                $data[] = $linesItem;
            }

            if ($list->retrieveOptions['headerRow'] == 1) {
                array_shift($data);
            }

            $datas = array_merge($datas, $data);
        }

        return $datas;
    }
    
    private function getContentFrom($uri, $list)
    {
        $content = '';
        
        if ($this->isRemoteURI($uri)) {
            
            $client = new Client([
                'base_uri' => $list->uri
            ]);
            
            $response = $client->request('GET', $list->urlSuffix, $list->requestOptions);
            
            $content = $response->getBody()->getContents();  
            
        } else {
            $content = file_get_contents($uri);
        }
        
        return $content;
    
    }
}
