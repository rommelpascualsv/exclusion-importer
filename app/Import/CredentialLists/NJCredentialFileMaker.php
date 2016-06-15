<?php

namespace App\Import\CredentialLists;

use App\Import\CredentialLists\Scrapers\NJCredential;

class NJCredentialFileMaker
{
    const DOWNLOAD_WAIT = 5;

    const FILE_HEADER = "profession_name,license_type,full_name,first_name,middle_name,last_name,name_suffix,gender,license_no,issue_date,expiration_date,addr_line_1,addr_line_2,addr_city,addr_state,addr_zipcode,addr_county,addr_phone,addr_fax,addr_email,license_status_name";

    /**
     * @var NJCredential
     */
    private $parser;

    private $dataFilePath;

    private $reScrapeFilePath;

    private $retryList = [];

    public function __construct($dataFilePath, $reScrapeFilePath)
    {
        $this->parser = new NJCredential();
        $this->dataFilePath = $dataFilePath;
        $this->reScrapeFilePath = $reScrapeFilePath;
    }

    /**
     * Starting point for building a file.
     */
    public function buildFile()
    {
        $initialResponse = $this->parser->crawlFormPage();
        if (! $initialResponse) {
            $this->output('red', 'Failed to crawl form page.');
            return;
        }

        $professions = $this->getReScrapeFileData();
        if ($professions && is_array($professions) && count($professions) > 0) {
            $this->output('black', '### ReScraped list found, retrying the list ###');
            $this->completeFile($initialResponse, $professions);
        } else {
            $this->output('black', '### Creating a new data file');
            $this->makeCSVFileHeader();
            $this->makeFile($initialResponse);
        }

        $this->makeReScrapeFile($this->retryList);
    }

    /**
     * @param \Guzzle\Http\Message\Response $initialResponse
     */
    private function makeFile($initialResponse)
    {
        $professions = $this->parser->getProfessions($initialResponse);
        foreach ($professions as $professionName) {
            $formPageResponse = $this->parser->selectProfession($initialResponse, $professionName);
            if(!$formPageResponse) {
                $this->output('red', 'Failed to select '. $professionName);
                continue;
            }

            list($licenseTypeList, $licenseStatusList) = $this->parser->getProfessionTypeAndStatusList($formPageResponse);
            foreach ($licenseTypeList as $licenseType) {
                foreach ($licenseStatusList as $licenseStatus) {
                    $this->makeRequests($formPageResponse, $professionName, $licenseType, $licenseStatus);
                }
            }
        }
    }

    /**
     * Completes data file
     *
     * @param \Guzzle\Http\Message\Response $initialResponse
     */
    private function completeFile($initialResponse, $professions)
    {
        foreach ($professions as $professionName => $professionLicenseList) {
            $formPageResponse = $this->parser->selectProfession($initialResponse, $professionName);
            if(!$formPageResponse) {
                $this->output('red', 'Failed to select '. $professionName);
                continue;
            }

            foreach ($professionLicenseList as $licenseType => $licenseStatusList) {
                foreach ($licenseStatusList as  $licenseStatus) {
                    $this->makeRequests($formPageResponse, $professionName, $licenseType, $licenseStatus);
                }
            }
        }
    }

    /**
     * @param \Guzzle\Http\Message\Response $formPageResponse
     * @param string $professionName
     * @param string $licenseType
     * @param string $licenseStatus
     */
    private function makeRequests($formPageResponse, $professionName, $licenseType, $licenseStatus)
    {
        $professionResponse = $this->parser->selectProfessionLicense($formPageResponse, $professionName, $licenseType);
        if(!$formPageResponse) {
            $this->output('red', 'Select profession license request failed for '. $professionName .' '. $licenseType);
            return;
        }

        $currentStatus = $professionName.' of type '. $licenseType. ' having status '. $licenseStatus;
        $this->output('black', 'Scraping: ' . $currentStatus);

        // Majority of the time we get error here due file creation on the server side at runtime.
        // So, if we get an error we track this and wait for sometime and move to the next profession.
        $response = $this->parser->fillForm($professionResponse, $professionName, $licenseType, $licenseStatus);
        if(! $response) {
            $this->output('red', 'Fill form request failed for ' . $currentStatus);
            $this->retryList = array_merge_recursive($this->retryList, [$professionName => [$licenseType => [$licenseStatus]]]);
            return;
        }

        $response = $this->parser->isTableDataExists($response);
        if(! $response) {
            $this->output('green', 'Empty results for '. $currentStatus);
            return;
        }

        $response = $this->parser->continueDownloadPage($response);
        if(! $response) {
            $this->output('red', 'Continue download page request failed for '. $currentStatus);
            $this->retryList = array_merge_recursive($this->retryList, [$professionName => [$licenseType => [$licenseStatus]]]);
            return;
        }

        $response = $this->parser->finalDownloadPage($response);
        if(! $response) {
            $this->output('red', 'Download page request failed for ' . $currentStatus);
            $this->retryList = array_merge_recursive($this->retryList, [$professionName => [$licenseType => [$licenseStatus]]]);
            return;
        }

        $response = $this->parser->getFileData($response);
        if(! $response) {
            $this->output('red', 'Download file request failed for ');
            $this->retryList = array_merge_recursive($this->retryList, [$professionName => [$licenseType => [$licenseStatus]]]);
            return;
        }

        $fileData = (string) $response->getBody();
        $this->makeCSVFile($fileData, $professionName, $licenseType);
        $this->output('green', 'Data appended to file');
        $this->output('blue', '### Item done: Going to sleep for '. self::DOWNLOAD_WAIT . ' seconds ###');
        sleep(self::DOWNLOAD_WAIT);
    }

    private function makeCSVFileHeader()
    {
        $fileHandler = fopen($this->dataFilePath,'w');
        $header = explode(',', trim(self::FILE_HEADER));
        fputcsv($fileHandler, $header);
    }

    /**
     * Append data to the csv file.
     *
     * @param string $fileData
     * @param string $professionName
     * @param string $licenseType
     * @return void
     */
    private function makeCSVFile($fileData, $professionName, $licenseType)
    {
        $fileHandler = fopen($this->dataFilePath,'a');

        $append = $professionName. '|' . $licenseType .'|';
        $fileLines = explode(PHP_EOL, trim($fileData));
        foreach($fileLines as $lineNumber => $line) {
            if($lineNumber !== 0) {
                $data = $append . $line;
                $val = explode('|', rtrim($data, '|'));
                fputcsv($fileHandler, $val);
            }
        }
        fclose($fileHandler);
    }

    /**
     * @param string $fileData
     */
    private function makeReScrapeFile($fileData)
    {
        $fileHandler = fopen($this->reScrapeFilePath,'w+');
        fwrite($fileHandler, serialize($fileData));
    }

    private function getReScrapeFileData()
    {
        if(file_exists($this->reScrapeFilePath)) {
            $fileData = unserialize(file_get_contents($this->reScrapeFilePath));
            return $fileData;
        }
        return [];
    }

    /**
     * @param string $color
     * @param string $text
     */
    private function output($color, $text)
    {
        // to fill the buffer for flushing
        echo str_pad(' ',4096);
        echo '<b style="color:'.$color.'">['.date('h:i:s', time()).'] '.$text.'</b><br/>';
        flush();
    }
}