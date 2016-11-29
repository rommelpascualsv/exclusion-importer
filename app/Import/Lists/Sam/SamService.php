<?php namespace App\Import\Lists\Sam;

class SamService
{

    const SAM_TABLE_NAME = 'sam_records';
    const SAM_TEMP_TABLE_NAME = 'sam_records_temp';
    private $fileName;


    public function __construct()
    {
        ini_set('memory_limit', '2048M');
        $this->fileName = self::generateFileName();
    }


    public function getFileName()
    {
        return $this->fileName;
    }


    /**
     * Creates the dynamic file name of Sam Records
     * with for of SAM_Exclusions_Public_Extract_{yyd}
     * where yy is the current year (e.g. 16 for 2016) and
     *       d is the day number of the year (e.g. 323 for Nov. 18, 2016)
     *
     * @return string
     * Example:
     *      SAM_Exclusions_Public_Extract_16323
     */
    private function generateFileName()
    {
        $currentDay = date('z') + 1;  // today
        $currentYear = date('y');
        $fileName = 'SAM_Exclusions_Public_Extract_'.$currentYear.$currentDay;
        return $fileName;
    }


    public function getUrl()
    {
        $uri = 'https://www.sam.gov/public-extracts/SAM-Public/' .$this->getFileName(). '.ZIP';
        return $uri;
    }


    public function getSamRecordsFromSource()
    {
        copy($this->generateUrl(), ExclusionListHttpDownloader::DEFAULT_DOWNLOAD_DIRECTORY . '/');
    }


    /**
     * Extract the content of the zip file to the same location.
     *
     * @param $zipFileLocation
     * @return bool
     */
    // This should be move to util class
    public function extractZip($zipFileLocation)
    {

        $zip = new \ZipArchive();
        if (! $response = $zip->open($zipFileLocation) === true) {
            error('Failed to open zip file ' . $response);
            return false;
        }

        if (! $zip->extractTo($dir = dirname($zipFileLocation))) {
            error('Failed to extract zip file.');
            return false;
        }

        info('Extracted to ' . dirname($zipFileLocation));

        $zip->close();
        return true;
    }


}