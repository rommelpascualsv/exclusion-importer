<?php

namespace App\Import\Lists\WashingtonDC;

use Streamlineverify\SVBot\BaseScraper;

class WashingtonDCParser
{
    /**
     * @var BaseScraper
     *
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

        //get the tables containing the past and current excluded entities
        $tableNodes = $this->scraper->xPathQuery("//table");
        $items = [];

        foreach ($tableNodes as $tableNode) {
            $headerNode = $this->scraper->xPathQuery("./thead/tr/th", $tableNode);
            $tableContents = $this->scraper->xPathQuery("./tbody/tr", $tableNode);
            $items = array_merge($items, $this->extractData($tableContents, $headerNode));
        }

        return $items;
    }

    private function getHeaders ($headerNode)
    {
        $headers = [];
        foreach ($headerNode as $header) {
            $value = rtrim(preg_replace(['/\s+|:/'], '', ($header->nodeValue)));
            if ($value != "") {
                $headers[] = $value;
            }
        }
        return $headers;
    }

    private function extractData($itemNode, $headerNode)
    {

        $itemData = [];
        $headers = $this->getHeaders($headerNode);

        foreach ($itemNode as $item) {

            $items = [];
            $entities = $this->scraper->xPathQuery("./td", $item);

            foreach ($entities as $key => $entity) {
                //remove nbsp; characters
                $content = html_entity_decode(str_replace("&nbsp;", "", htmlentities($entity->nodeValue, null, 'utf-8')));
                $items[$headers[$key]] = trim(preg_replace('/\s+|:/', ' ', ($content)));
            }
            $itemData[] = $this->processData($items);
        }

        return $itemData;
    }

    private function processData($item)
    {
        $model = new WashingtonDCModel();
        if (isset($item['ActionDate'])) {
            $model->setActionDate($item['ActionDate']);
        }

        if (isset($item['Principals'])) {
            $model->setPrincipals($item['Principals']);
        }

        if (isset($item['TerminationDate﻿﻿'])) {
            $model->setTerminationDate($item['TerminationDate﻿﻿']);
        }

        if (isset($item['NameofCompany'])) {
            $model->setCompanies($item['NameofCompany']);
        }

        if (isset($item['PrincipalAddress'])) {
            $model->setAddresses($item['PrincipalAddress']);
        }

        if (isset($item['NameofIndividual'])) {
            $model->setNames($item['NameofIndividual']);
        }

        return $model->toArray();
    }

}
