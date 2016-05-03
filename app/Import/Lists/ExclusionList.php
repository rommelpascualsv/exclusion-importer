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
    public $npiColumnName;
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
    		
    		if ($this->npiColumnName) {
    			$npiColumnIndex = $this->getNpiColumnIndex($this->npiColumnName);
    			$row[$npiColumnIndex] = $this->handleNpiValues($row[$npiColumnIndex]);
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
     * Make a JSON array string representation for a given array, otherwise return a string value.
     *
     * @param array $npi the npi array
     * @return string the JSON array string representation or the string value
     */
    private function handleNpiValues(array $npi)
    {
    	if (empty($npi)) {
    		return "";
    	} else if (count($npi) == 1) {
    		return head($npi);
    	}
    	return json_encode($npi);
    }
    
    /**
     * Retrieves the npi column index for a given npi column name 
     * 
     * @param string $npiColumnName
     * @return int the npi column index
     */
    private function getNpiColumnIndex($npiColumnName)
    {
    	$index = 0;
    	foreach ($this->fieldNames as $field) {
    		
    		if ($field === $npiColumnName) {
    			break;
    		}
    		
    		$index++;
    	}
    	
    	return $index;
    }
}
