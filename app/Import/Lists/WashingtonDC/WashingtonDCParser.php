<?php

namespace App\Import\Lists\WashingtonDC;

use Streamlineverify\SVBot\BaseScraper;

class WashingtonDCParser
{
    /**
     * @var BaseScraper
     */
    private $scraper;

    private $headers = [
        'Host' => 'ocp.dc.gov'
    ];

    const BASE_URL = "http://ocp.dc.gov";

    const FORM_URL = "page/excluded-parties-list";

    /**
     * WashingtonDCParser constructor.
     */
    public function __construct()
    {
        $this->scraper = new BaseScraper(self::BASE_URL);
    }

    /**
     * @return \Guzzle\Http\Message\Response
     */
    public function crawlFormPage()
    {
        $response = $this->scraper->fetchGetResource(self::FORM_URL, $this->headers);

        return $response;
    }

    public function getItems($response) {

        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        //get pagination nodes and get the max number of pages
        $headerNodes = $this->scraper->xPathQuery("//h3");
        $currentTableNode = [];
        foreach ($headerNodes as $header) {
            if (preg_match('/Current/',$header->nodeValue)) {
                $currentTableNode['individual'] =  $this->extractData($this->scraper->xPathQuery("following-sibling::table/tbody", $header)->item(0));
                $currentTableNode['company'] = $this->extractData($this->scraper->xPathQuery("following-sibling::table/tbody", $header)->item(1));
            }
        }

        return array_merge($currentTableNode['individual'], $currentTableNode['company']);
    }

    private function extractData($tableNode)
    {

        $itemData = [];
//        $exclusionDateNode = $this->scraper->xPathQuery("//h3")->item(0);
//        if ($exclusionDateNode) {
//            $itemData['date-excluded'] = preg_replace(['/\s+|-/'], '', ($exclusionDateNode->nodeValue));
//        }

        foreach ($tableNode->childNodes as $rows) {
            $item = [];
            foreach ($rows->childNodes as $column) {
                $item[] =  preg_replace(['/\s+|-/'], '', ($column->nodeValue));
            }

            $itemData[] = $item;
        }
//
//        $tableNodes = $this->scraper->xPathQuery("./dl/dt", $item);
//        foreach ($tableNodes as $tableItem) {
//            $key = preg_replace('/\s|:/', '', strtolower($tableItem->nodeValue));
//            $value = $this->scraper->xPathQuery("./following-sibling::dd", $tableItem)->item(0);
//            $itemData[$key] = $value->nodeValue;
//        }

        return $itemData;
    }


    private function processData($item)
    {
        $model = new WashingtonDCModel();
        if (isset($item['date-excluded'])) {
            $model->setDateExcluded($item['date-excluded']);
        }

        if (isset($item['term'])) {
            $model->setTerm($item['term']);
        }

        if (isset($item['term']) && isset($item['date-excluded'])) {
            $model->setExclusionDate($item['term'], $item['date-excluded']);
        }

        if (isset($item['companies'])) {
            $model->setCompanies($item['companies']);
        }

        if (isset($item['addresses'])) {
            $model->setAddresses($item['addresses']);
        }

        if (isset($item['summary'])) {
            $model->setSummary($item['summary']);
        }

        if (isset($item['people'])) {
            $model->setNames($item['people']);
        }

        return $model->toArray();
    }

}
