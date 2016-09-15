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

        return $this->fileReader->readRecords($fileName, $list->retrieveOptions);
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
        $filePath = storage_path('app') . '/' . $prefix . '_temp.xls';
        
        try {
            
            $newFilePath = storage_path('app') . '/' . $prefix . '_temp.csv';
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            if (file_exists($newFilePath)) {
                unlink($newFilePath);
            }
            
            file_put_contents($filePath, $fileContent);
            
            exec("ssconvert $filePath $newFilePath", $output, $returnCode);
            
            if ($returnCode !== 0 || ! file_exists($newFilePath)) {
                throw new DataCsvConversionException('Unable to convert the input file\'s contents to a parsable format');
            }
            
            return $newFilePath;
            
        } finally {
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

    }
}
