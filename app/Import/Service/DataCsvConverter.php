<?php namespace App\Import\Service;

use App\Import\Lists\ExclusionList;
use App\Import\Service\File\CsvFileReader;


class DataCsvConverter
{

    /**
     * @var	\App\Import\Service\File\FileReader	$fileReader
     */
    protected $fileReader;


    /**
     * Constructor
     *
     * @param	CsvFileReader	$fileReader
     */
    public function __construct(CsvFileReader $fileReader)
    {
        $this->fileReader = $fileReader;
    }


    /**
     * Fill the ExclusionList object's data property
     *
     * @param	ExclusionList	$list
     * @param	$data
     * @return	ExclusionList
     */
    public function convertData(ExclusionList $list, $data)
    {
        $fileName = $this->convertDataToCsvFile($data, $list->dbPrefix);

        list($fileHeaders, $data) = $this->fileReader->readRecords($fileName, $list->retrieveOptions);

        return $data;
    }


    /**
     * Convert a file to csv
     *
     * @param	string	$fileContent
     * @param	string	$prefix
     * @return	string
     */
    protected function convertDataToCsvFile($fileContent, $prefix)
    {
        $directory = DATAPATH . $prefix . '_files';

        if ( ! is_dir($directory))
        {
            mkdir($directory, 02777);

            chmod($directory, 02777);
        }

        file_put_contents($directory . '/temp.xls', $fileContent);

        exec("ssconvert {$directory}/temp.xls {$directory}/temp.csv");

        unlink("{$directory}/temp.xls");

        return "{$directory}/temp.csv";
    }

}
