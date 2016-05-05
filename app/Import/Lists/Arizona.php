<?php namespace App\Import\Lists;

class Arizona extends ExclusionList
{
    public $dbPrefix = 'az1';

    public $uri = "https://web.archive.org/web/20150609233844/http://www.azahcccs.gov/OIG/ExludedProviders.aspx";

    public $type = 'html';

    public $retrieveOptions = [
        'htmlFilterElement' => 'table[class="datatable"]',
        'rowElement'        => 'tr',
        'columnElement'     => 'td',
        'headerRow'         => 0,
        'offset'            => 0
    ];

    public $fieldNames = [
        'last_name_company_name',
        'middle',
        'first_name',
        'term_date',
        'specialty',
        'npi_number'
    ];
    
    public $npiColumnName = "npi_number";
    
    private $npiRegex = "/1\d{9}\b/";
    
    public function preProcess()
    {
    	$this->parse();
    	parent::preProcess();
    }
    
	public function parse()
    {
    	$rows = $this->data;
    	
    	$data = [];
    	foreach ($rows as $key => $value) {
    		//Middle Initial
    		array_splice($value, 1, 0,  $this->extractFirstMiddle(str_replace(["\xA0", "\xC2"], '', trim($value[1])))[1]);
    		$value[0] = str_replace(["\xA0", "\xC2"], '', trim($value[0]));
    		//First Name
    		$value[2] = $this->extractFirstMiddle(str_replace(["\xA0", "\xC2"], '', trim($value[2])))[0];
    		$value[3] = str_replace(["\xA0", "\xC2"], '', trim($value[3]));
    		$value[4] = str_replace(["\xA0", "\xC2"], '', trim($value[4]));
    		$value[5] = str_replace(["\xA0", "\xC2"], '', trim($value[5]));
    		
    		$data[] = $this->handleRow($value);
    	}
    
    	$this->data = $data;
    }
    
    private function extractFirstMiddle($name)
    {
    	$firstName = str_replace('.', '', $name);
    	$nameArr = explode(' ', $firstName);
    	if (count($nameArr) == 1) {
    		$nameArr[1] = '';
    	}
    	
    	return $nameArr;
    }
    
    /**
     * Handles the data manipulation of a record array.
     *
     * @param array $row the array record
     * @return array $row the array record
     */
    private function handleRow($row)
    {
    	// set npi number array
    	$row = $this->setNpi($row);
    
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
    	preg_match_all($this->npiRegex, $row[5], $npi);
    
    	$row[5] = $npi[0];
    
    	return $row;
    }
}
