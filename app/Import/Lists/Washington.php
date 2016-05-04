<?php namespace App\Import\Lists;

class Washington extends ExclusionList
{
    public $dbPrefix = 'wa1';

    public $pdfToText = 'java -Dfile.encoding=utf-8 -jar ../etc/tabula.jar -p all -u -g -r'; //'pdftotext -layout -nopgbrk';

    public $uri = 'http://www.hca.wa.gov/medicaid/provider/documents/termination_exclusion.pdf';

    public $type = 'pdf';

    /**
     * @var field names
     */
    public $fieldNames = [
        'last_name',
        'first_name',
        'middle_etc',
        'entity',
        'provider_license',
        'npi',
        'termination_date',
        'termination_reason',
    	'provider_number'	
    ];

    /**
     * @var row offset
     */
    public $retrieveOptions = [
        'offset'    => 2
    ];

    public $hashColumns = [
        'last_name',
        'first_name',
        'entity',
        'provider_license',
        'npi',
        'termination_date'
    ];

    public $dateColumns = [
       'termination_date' => 6
    ];

    public $npiColumnName = 'npi';
    
    private $npiRegex = "/1\d{9}\b/";
    
    private $commaRegex = "/^(,+\s)?,?|(,+\s)?,?$/";
    
    private $spacesRegex = "!\s+!";
    
    /**
     * @var institution special cases
     */
    private $institutions = [
    		'Wheelchairs Plus',
    		'AA Adult Family Home',
    		'Our House Adult Family Home/',
    		'Wheelchairs Plus',
    		'/Fairwood Care'
    ];
    
    private $business;

    /**
     * @inherit preProcess
     */
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    /**
     * @param string
     * @return boolean
     */
    public function valueIsLastNameFirst($name)
    {
        return strpos($name, ', ') !== false;
    }

    /**
     * @param string
     * @return array
     */
    public function parseLastNameFirst($name)
    {
        $completeName = explode(', ', $name);
        $names[] = $completeName[0];

        if (isset($completeName[1])) {
            $firstMiddle = explode(' ', $completeName[1], 2);

            if (count($firstMiddle) == 1) {
                $firstMiddle[] = '';
            }

            $names = array_merge($names, $firstMiddle);
        }

        return $names;
    }

    /**
     * @param string
     * @return array
     */
    public function textNewLineToArray($string)
    {
        return preg_split('/(\r)?\n(\s+)?/', trim($string));
    }

    /**
     * @param string csv
     * @return array
     */
    public function csvToArray($string)
    {
        $string = preg_replace('/[\r\n]+/', ' ', $string);
        $string = preg_replace('!\s+!', ' ', $string);
        $array = str_getcsv($string);
        return array_map('trim', $array);
    }

    /**
     * @param string name
     * @return array
     */
    private function parseName($name)
    {
        $names = [];

        if ($this->valueIsLastNameFirst($name)) {
            return $this->parseLastNameFirst($name);
        }

        $names = explode(' ', $name);
        $names[] = '';

        return $names;
    }

    /**
     * @param string business
     * @return array
     */
    private function parseBusiness($business)
    {
        $name = ['', '', ''];
        $name[] = $business;
        return $name;
    }

    /**
     * @param array data rows
     * @return array
     */
    private function override(array $value)
    {
        $data = '';

        //if combination of business and name
        if ($this->business) {
            $name = $this->parseName($value[0]);
            $name[] = $this->business;
            $value = array_offset($value, 1);
            return array_merge($name, $value);
        }

        //if Name without business
        if ($this->valueIsLastNameFirst($value[0])) {
            $name = $this->parseName($value[0]);
            $name[] = '';
            $value = array_offset($value, 1);
            return array_merge($name, $value);
        }

        //if business w/o name
        $name = $this->parseBusiness($value[0]);
        $value = array_offset($value, 1);

        return array_merge($name, $value);
    }

    /**
     * @inherit parse
     */
    protected function parse()
    {
        $data = [];
        $rows = $this->textNewLineToArray($this->data);

        //array offset
        $rows = array_offset($rows, $this->retrieveOptions['offset']);

        foreach ($rows as $key => $value) {
            $this->business = '';
            // convert csv string to array
            $row = $this->csvToArray($value);

            // Check for combination of name and business
            foreach ($this->institutions as $ins) {
                if (strpos($row[0], $ins) !== false) {
                    $row[0] = str_replace($ins, '', $row[0]);
                    $this->business = str_replace('/', '', $ins);
                    break;
                }
            }

            // handles the population of name and business values
            $row = $this->override($row);
            
            // handles npi number values 
            $row = $this->handleRow($row);
            
            $data[] = $row;
        }

        $this->data = $data;
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
    	$providerNo = preg_replace($this->npiRegex, "", trim($row[5]));
    	
    	// remove commas
    	$providerNo = preg_replace($this->commaRegex, "", trim($providerNo));
    	
    	// remove duplicate spaces in between numbers
    	$row[] = preg_replace($this->spacesRegex, " ", trim($providerNo));
    	
    	return $row;
    }
    
    /**
     * Set the npi numbers by extracting the valid npi values from the npi column
     *   
     * @param array $row the array record
     * @return array $row the array record
     */
    private function setNpi($row)
    {
    	// extract npi number/s
    	preg_match_all($this->npiRegex, $row[5], $npi);
    	 
    	$row[5] = $npi[0];
    	
    	return $row;
    }
}
