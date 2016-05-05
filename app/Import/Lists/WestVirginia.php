<?php namespace App\Import\Lists;

class WestVirginia extends ExclusionList
{
    /**
     * @var string
     */
    public $dbPrefix = 'wv2';

    /**
     * @var string
     */
    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/west-virginia/wv2.xlsx';

    public $pdfToText = 'java -Dfile.encoding=utf-8 -jar ../etc/tabula.jar -p all -u -g -r'; //'pdftotext -layout -nopgbrk';

    public $type = 'pdf';

    /**
     * @var array
     */
    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    /**
     * @var array
     */
    public $fieldNames = [
        'npi_number',
        'full_name',
        'first_name',
        'middle_name',
        'last_name',
        'generation',
        'credentials',
        'provider_type',
        'city',
        'state',
        'exclusion_date',
        'reason_for_exclusion',
        'reinstatement_date',
        'reinstatement_reason',
    	'provider_number'	
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'npi_number',
        'full_name',
        'first_name',
        'middle_name',
        'last_name',
        'exclusion_date',
        'reinstatement_date',
    ];

    /**
     * @var array
     */
    public $dateColumns = [
        'exclusion_date' => 10,
        'reinstatement_date' => 12
    ];

    public $npiColumnName = "npi_number";
    
    private $npiRegex = "/1\d{9}\b/";
    
    private $symbolsRegex = "/^((,|\/)+\s)?(,|\/)?|((,|\/)+\s)?(,|\/)?$/";
    
    private $spacesRegex = "!\s+!";
    
    /**
     * @inherit preProcess
     */
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    /**
     * @param string csv
     * @return array
     */
    public function lineToaArray($string)
    {
        return preg_split('/(\r)?\n(\s+)?/', trim($string));

    }

    private function csvToArray($csv)
    {
        return str_getcsv($csv);
    }

    private function buildData($string)
    {
        // Convert new line to array
        $array = $this->lineToaArray($string);

        return array_map(function ($item) {
            //Each row contains csv convert it to array
            $row = $this->csvToArray($item);

            if (strpos($row[0], 'NPI') === false) {

                if (count($row) == 15) {
                    unset($row[11]);
                    $row = array_values($row);
                }

                foreach ($this->checkLastFirstName($row) as $value) {
                    if ($value) {
                        return $this->checkLastFirstName($row);
                    }
                }
            }

        }, $array);
    }

    /**
     * Remove header of the array
     * 
     * @param array $array
     * @return array the array data without the header
     */
    private function removeHeader(array $array)
    {
        $data = [];

        foreach ($array as $key => $value) {
            if ($value) {
                $data[] = $value;
            }
        }

        return $data;
    }


    private function checkLastFirstName(array $row)
    {
        if ($row[2] && $row[4]) {
            $row[1] = '';
        }

        return $row;
    }

    /**
     * Parse data if Entity or Individual
     * @return void data method
     */
    protected function parse()
    {
        $data = [];
        foreach ($this->data as $key => $value) {
            $data = array_merge($data, $this->removeHeader($this->buildData($value)));
        }
        
        $this->data = array_map(function ($row) {
        	return $this->handleRow($row);
        }, $data);
    }
    
    /**
     * Handles the data manipulation of a record array.
     *
     * @param array $row the array record
     * @return array $row the array record
     */
    private function handleRow($row)
    {
    		
    	// set provider number
    	$row = $this->setProviderNo($row);
    
    	// set npi number array
    	$row = $this->setNpi($row);
    		
    	return $row;
    }
    
    /**
     * Set the provider number by clearing the unnecessary characters
     *
     * @param array $row the array record
     * @return array $row the array record
     */
    private function setProviderNo($row)
    {
    	// remove valid npi numbers
    	$providerNo = preg_replace($this->npiRegex, "", trim($row[0]));
    		
    	// remove commas
    	$providerNo = preg_replace($this->symbolsRegex, "", trim($providerNo));
    		
    	// remove duplicate spaces in between numbers
    	$row[] = preg_replace($this->spacesRegex, " ", trim($providerNo));
    		
    	return $row;
    }
    
    /**
     * Set the npi numbers
     *
     * @param array $row the array record
     * @return array $row the array record
     */
    private function setNpi($row)
    {
    	// extract npi number/s
    	preg_match_all($this->npiRegex, $row[0], $npi);
    
    	$row[0] = $npi[0];
    		
    	return $row;
    }
}
