<?php

namespace App\Import\CredentialLists\Scrapers;

use Streamlineverify\SVBot\BaseScraper;

class NJCredential
{
    /**
     * @var BaseScraper
     */
    private $scraper;

    /**
     * @var string
     */
    private $cookies;

    const BASE_URL = "https://newjersey.mylicense.com";

    const FORM_URL = "Verification_4_6/Verification_Bulk_4_6/Search.aspx?facility=N";

    /**
     * @var array
     */
    private $headers = [
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Encoding' => 'gzip, deflate',
        'Accept-Language' => 'en-US,en;q=0.8',
        'Host' => 'newjersey.mylicense.com',
        'Origin' => 'https://newjersey.mylicense.com'
    ];

    /**
     * NJParser constructor.
     */
    public function __construct()
    {
        $this->scraper = new BaseScraper(self::BASE_URL, ['exceptions' => false]);
        $this->cookies = $this->getCookies();
        $this->headers['Cookie'] = $this->cookies;
    }

    /**
     * Crawl the form page to get the cookies which are going to be used for the upcoming
     * requests.
     *
     * @return string
     **/
    public function getCookies()
    {
        $response = $this->crawlFormPage();
        return $response->getSetCookie();
    }

    /**
     * @return \Guzzle\Http\Message\Response
     */
    public function crawlFormPage()
    {
        $response = $this->scraper->fetchGetResource(self::FORM_URL, $this->headers);
        return $response;
    }

    /**
     * Fetches the list of professions from the form page.
     *
     * @param \Guzzle\Http\Message\Response $response
     * @return \Guzzle\Http\Message\Response | null
     */
    public function getProfessions($response)
    {
        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        $professions = [];
        $professionsNode = $this->scraper->xPathQuery("//select[@name='t_web_lookup__profession_name']/option");
        foreach ($professionsNode as $key => $optionNode) {
            if ($value = $optionNode->getAttribute('value')) {
                $professions[] = $value;
            }
        }

        return $professions;
    }

    /**
     * Fetches the type and select values from profession response for iteration.
     *
     * @param \Guzzle\Http\Message\Response $response
     * @return \Guzzle\Http\Message\Response | null
     */
    public function getProfessionTypeAndStatusList($response)
    {
        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        $licenseTypeList = [];
        $licenseStatusList = [];

        $licenseTypeNode = $this->scraper->xPathQuery("//select[@name='t_web_lookup__license_type_name']/option");
        foreach ($licenseTypeNode as $key => $optionNode) {
            if ($value = $optionNode->getAttribute('value')) {
                $licenseTypeList[] = $value;
            }
        }

        $licenseStatusNode = $this->scraper->xPathQuery("//select[@name='t_web_lookup__license_status_name']/option");
        foreach ($licenseStatusNode as $key => $optionNode) {
            if ($value = $optionNode->getAttribute('value')) {
                $licenseStatusList[] = $value;
            }
        }

        return [$licenseTypeList, $licenseStatusList];
    }

    /**
     * Fetches the page where all license types of a specific profession are available
     *
     * @param \Guzzle\Http\Message\Response $response
     * @param string $professionName
     * @return \Guzzle\Http\Message\Response|null
     *
     */
    public function selectProfession($response, $professionName)
    {
        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        $postData = $this->getHiddenInputFields();
        $postData['selectname'] = 'OAG Services from A - Z';
        $postData['t_web_lookup__profession_name'] = $professionName;
        $response = $this->scraper->fetchPostResource(self::FORM_URL, $postData, $this->headers);
        return $response;
    }

    /**
     * Fetches the page where all license types status of a specific profession are available
     *
     * @param \Guzzle\Http\Message\Response $response
     * @param string $professionName
     * @param string $licenseType
     * @return \Guzzle\Http\Message\Response|null
     */
    public function selectProfessionLicense($response, $professionName, $licenseType)
    {

        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        // Post FormData.
        $postData = $this->getHiddenInputFields();
        $postData['selectname'] = "OAG Services from A - Z";
        $postData['t_web_lookup__profession_name'] = $professionName;
        $postData['t_web_lookup__license_type_name'] = $licenseType;
        $response = $this->scraper->fetchPostResource('Verification_4_6/Verification_Bulk_4_6/Search.aspx?facility=N',
            $postData, $this->headers);
        return $response;
    }

    /**
     * Fetches the download button.
     *
     * @param \Guzzle\Http\Message\Response $response
     * @param string $professionName
     * @param string $licenseType
     * @param string $licenseStatus
     * @return \Guzzle\Http\Message\Response | null
     */
    public function fillForm($response, $professionName, $licenseType, $licenseStatus)
    {
        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        // Post FormData.
        $postData = $this->getHiddenInputFields();
        $postData['selectname'] = 'OAG Services from A - Z';
        $postData['t_web_lookup__profession_name'] = $professionName;
        $postData['t_web_lookup__license_type_name'] = $licenseType;
        $postData['t_web_lookup__license_status_name'] = $licenseStatus;
        $postData['sch_button'] = "Search";
        $response = $this->scraper->fetchPostResource(self::FORM_URL, $postData, $this->headers);
        return $response;
    }

    /**
     * Checks if the table data exists.
     *
     * @param \Guzzle\Http\Message\Response $response
     * @return \Guzzle\Http\Message\Response | false $response
     */
    public function isTableDataExists($response)
    {
        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        $tableNode = $this->scraper->xPathQuery("//table[@id='datagrid_results']/tr");
        if ($tableNode->length > 2) {
            return $response;
        }
        return false;
    }

    /**
     * Fetches the continue download button page
     *
     * @param \Guzzle\Http\Message\Response $response
     * @return \Guzzle\Http\Message\Response | null $response
     */
    public function continueDownloadPage($response)
    {
        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        $postData = $this->getHiddenInputFields();
        $postData['selectname'] = 'OAG Services from A - Z';
        $postData['btnBulkDownLoad'] = 'Download List';
        $response = $this->scraper->fetchPostResource('Verification_4_6/Verification_Bulk_4_6/SearchResults.aspx',
            $postData, $this->headers);
        return $response;
    }

    /**
     * Fetches the final/last download button page with a
     * actual form to download file.
     *
     * @param \Guzzle\Http\Message\Response $response
     * @return \Guzzle\Http\Message\Response | null $response
     */
    public function finalDownloadPage($response)
    {
        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        $postData = $this->getHiddenInputFields();
        $postData['btnContinue'] = 'Continue';
        $response = $this->scraper->fetchPostResource('Verification_4_6/Verification_Bulk_4_6/Confirmation.aspx?from_page=SearchResults.aspx',
            $postData, $this->headers);
        return $response;
    }

    /**
     * Fills the form to return file data.
     *
     * @param \Guzzle\Http\Message\Response $response
     * @return \Guzzle\Http\Message\Response | null $response
     */
    public function getFileData($response)
    {

        $html = (string)$response->getBody();
        $this->scraper->setDom($html);

        $postData = $this->getHiddenInputFields();
        $postData['selectname'] = "Continue";
        $postData['sch_button'] = "Download";
        $postData['filetype'] = "delimitedtext";
        $response = $this->scraper->fetchPostResource(
            'Verification_4_6/Verification_Bulk_4_6/PrefDetails.aspx',
            $postData,
            $this->headers
        );

        return $response;
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
        $inputNodes = $this->scraper->xPathQuery("//form[@id='TheForm']/input");

        foreach ($inputNodes as $node) {
            $name = $node->getAttribute('name');
            $inputArray[$name] = $node->getAttribute('value');
        }
        return $inputArray;
    }
}