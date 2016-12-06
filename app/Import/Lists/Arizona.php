<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class Arizona extends ExclusionList
{
    public $dbPrefix = 'az1';

    public $uri = "https://web.archive.org/web/20150609233844/http://www.azahcccs.gov/OIG/ExludedProviders.aspx";

    public $type = 'html';

    public $shouldHashListName = true;
    
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
    
    protected $npiColumnName = "npi_number";
    
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
    		//Last Name Company name
    		$value[0] = str_replace(["\xA0", "\xC2"], '', trim($value[0]));
    		//First Name
    		$value[2] = $this->extractFirstMiddle(str_replace(["\xA0", "\xC2"], '', trim($value[2])))[0];
    		//Term Date
			$value[3] = $this->formatDate($value[3]);
    		//Specialty
    		$value[4] = str_replace(["\xA0", "\xC2"], '', trim($value[4]));
    		//NPI
    		$value[5] = str_replace(["\xA0", "\xC2"], '', trim($value[5]));
    		
    		$npiColumnIndex = $this->getNpiColumnIndex();
    		
    		// set npi number array
    		$value = PNHelper::setNpiValue($value, PNHelper::getNpiValue($value, $npiColumnIndex), $npiColumnIndex);
    			
    		$data[] = $value;
    	}
    
    	$this->data = $data;
    }
    
    /**
     * Extracts the first name and middle initial from the first name column.
     * 
     * @param string $name contains both the first name and middle initial
     * @return array $nameArr the array that contains the first name and the middle initial
     */
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
	 * Format date to Y-m-d
	 *
	 * @param $value
	 * @return bool|string
	 */
	private function formatDate($value)
	{
		$date = date_create(str_replace(["\xA0", "\xC2"], '', trim($value)));
		$formattedDate = date_format($date, 'Y-m-d');
		return $formattedDate;
	}
}
