<?php

namespace App\Import\Lists\HealthMil;

use Streamlineverify\SVBot\BaseScraper;

class HealthMilParser
{
    /**
     * @var BaseScraper
     */
    private $scraper;

    private $headers = [
        'Host' => 'www.health.mil'
    ];

    const BASE_URL = "https://www.health.mil";

    const FORM_URL = "Military-Health-Topics/Access-Cost-Quality-and-Safety/Quality-And-Safety-of-Healthcare/Program-Integrity/Sanctioned-Providers";

    const SESS_COOKIE_NAME = 'ASP.NET_SessionId';

    const DOMAIN = 'health.mil';
    /**
     * HealthMilParser constructor.
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

    public function getViewAllList($response)
    {
        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        $cookie = $response->getSetCookie();
        $this->headers['Cookie'] = $cookie;

        $this->scraper->setSessCookie($cookie, self::SESS_COOKIE_NAME, self::DOMAIN);

        $postData = $this->getHiddenInputFields();
        $postData['ctl01$txtSearch'] = "";
        $postData['pagecolumns_0$content_2$txtName'] = "";
        $postData['pagecolumns_0$content_2$ddlCountry'] = "{D37DF6CE-B49A-469C-BA45-2A6E758EF1AD}";
        $postData['pagecolumns_0$content_2$ddlState'] = "";
        $postData['pagecolumns_0$content_2$btnViewAll'] = "View All";

        return $this->scraper->fetchPostResource(self::FORM_URL, $postData,$this->headers);

    }

    public function getItems($response) {

        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        $paginationNodes = $this->scraper->xPathQuery("//span[@id='pagecolumns_0_content_2_dpResults']/a");
        $lastPageLink = $paginationNodes->item($paginationNodes->length - 1)->getAttribute('href');

        $maxPage = explode('=', $lastPageLink)[1];

        $items = [];

        for ($i = 1; $i <= $maxPage; $i++) {
            $response = $this->scraper->fetchGetResource(self::FORM_URL . '?page=' . $i . '#pagingAnchor',$this->headers);
            $items = array_merge($items, $this->getNodeItems($response));
        }

        return $items;
    }

    public function getNodeItems($response)
    {
        $items = [];
        $html = (string)$response->getBody();
        $this->scraper->setDom($html);
        $itemNodes = $this->scraper->xPathQuery("//section[@class='refItem']");
        foreach ($itemNodes as $item) {
            $processData = $this->extractData($item);
            $items[] = $this->processData($processData);
        }
        return $items;
    }

    private function extractData($item)
    {
        $itemData = [];
        $exclusionDateNode = $this->scraper->xPathQuery("./h3/text()", $item)->item(0);
        if ($exclusionDateNode) {
            $itemData['date-excluded'] = preg_replace(['/\s+|-/'], '', ($exclusionDateNode->nodeValue));
        }

        $tableNodes = $this->scraper->xPathQuery("./dl/dt", $item);
        foreach ($tableNodes as $tableItem) {
            $key = preg_replace('/\s|:/', '', strtolower($tableItem->nodeValue));
            $value = $this->scraper->xPathQuery("./following-sibling::dd", $tableItem)->item(0);
            $itemData[$key] = $value->nodeValue;
        }

        return $itemData;
    }


    private function processData($item)
    {
        $model = new HealthMilModel;
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

    /**
     * Gets the hidden input fields data from the pages.
     * Also uses xpath queries on Domdocument.
     *
     * @return array
     */
    private function getHiddenInputFields()
    {
        $inputArray = array();
        $inputNodes = $this->scraper->xPathQuery("//form[@id='mainform']/div/input");
        foreach ($inputNodes as $node) {
            $name = $node->getAttribute('name');
            $inputArray[$name] = $node->getAttribute('value');
        }

        return $inputArray;
    }
}
