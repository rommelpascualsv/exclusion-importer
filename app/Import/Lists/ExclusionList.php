<?php namespace App\Import\Lists;

use App\Import\Service\Exclusions\RetrieverFactory;

abstract class ExclusionList
{
    /**
     * Database table's prefix (e.g. {$dbPrefix}_records)
     *
     * @var string
     */
    public $dbPrefix;

    /**
     * File's public uri
     *
     * @var string
     */
    public $uri;

    /**
     * @var array
     */
    public $retrieveOptions = [];

    /**
     * @var array
     */
    public $headerOptions = [];

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var
     */
    public $fileHeaders;

    /**
     * Columns to create a hash from
     *
     * @var array
     */
    public $hashColumns = [];
    public $dateColumns = [];
    public $npiColumn = -1;
    public $fieldNames = [];
    public $urlSuffix = '';
    public $requestOptions = [];

    //if set to true in child class it protects getting matching hashes on different exclusion lists
    public $shouldHashListName = false;
    public $type;
    public $nodes = [];
    public $nodeMap = [];

    protected $retrieverFactory;

    public function __construct()
    {
        $this->retrieverFactory = new RetrieverFactory;
    }

    public function retrieveData()
    {
        $retriever = $this->retrieverFactory->make($this->type);
        $this->data = $retriever->retrieveData($this);
    }

    public function convertDatesToMysql($row, $dateColumns)
    {
    	foreach ($dateColumns as $index) {
    		if (strtotime($row[$index])) {
    			$date = new \DateTime($row[$index]);
    			$row[$index] = $date->format('Y-m-d');
    		} else {
    			$row[$index] = null;
    		}
    	}

    	return $row;
    }

    public function preProcess()
    {
    	$this->data = array_map(function ($row) {
    	
    		if (count($this->dateColumns) > 0) {
    			$row = $this->convertDatesToMysql($row, $this->dateColumns);
    		}
    		
    		if ($this->npiColumn != -1) {
    			$row[$this->npiColumn] = $this->handleNpiValues($row[$this->npiColumn]);
    		}
    		
    		$row = array_combine($this->fieldNames, $row);
    		return $row;
    	
    	}, $this->data);
    }

    public function postProcess()
    {
    }
    public function postHook()
    {
    }
    
    /**
     * Retrieves the array string for a given space-delimeted value
     * 
     * @param string $value the npi space-delimeted value
     * @return array the array string npi values
     */
    protected function getNpiValues($value)
    {
    	return explode(" ", trim($value));
    }
    
    /**
     * Make a JSON array string representation for a given array, otherwise return the single value
     *
     * @param string $value the npi space-delimeted value
     * @return string the JSON array string representation or the single value
     */
    private function handleNpiValues($value)
    {
    	$npi = $this->getNpiValues($value);
    	
    	if (count($npi) == 1) {
    		return head($npi);
    	}
    	return json_encode($npi);
    }
}
