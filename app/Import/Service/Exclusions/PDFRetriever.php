<?php namespace App\Import\Service\Exclusions;

use App\Import\Lists\ExclusionList;
use GuzzleHttp\Client;

class PDFRetriever extends Retriever
{

    /**
     * @param \GuzzleHttp\Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }


    /**
     * @var    \GuzzleHttp\client $httpClient
     */
    protected $httpClient;


    public function retrieveData(ExclusionList $list)
    {
        $folder = DATAPATH . $list->dbPrefix . '_files';

        $this->httpClient->get($list->uri)
            ->setResponseBody("{$folder}/{$list->dbPrefix}.pdf")
            ->send();

        $contents = shell_exec($list->pdfToText . ' ' . DATAPATH . $list->dbPrefix . '_files/' . $list->dbPrefix . '.pdf -');

        $list = $list->parse($contents);

        if (count($list->dateColumns) > 0)
        {
            $list->data = $this->convertDatesToMysql($list->data, $list->dateColumns);
        }

        return $list;
    }

}
