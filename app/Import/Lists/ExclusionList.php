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

    public function convertDatesToMysql($data, $dateColumns)
    {
        return array_map(function ($row) use ($dateColumns) {

            foreach ($dateColumns as $columnName => $index) {
                $columnValue = $row[$index];
                if (strtotime($columnValue)) {
                    $date = new \DateTime($columnValue);
                    $row[$index] = $date->format('Y-m-d');
                } else {
                    $row[$index] = $this->valueForUnparsableDate($columnName, $columnValue, $row);
                }
            }

            return $row;

        }, $data);
    }

    public function convertToAssoc()
    {
        $this->data = array_map(function ($item) {
            
            return array_combine($this->fieldNames, $item);

        }, $this->data);
    }

    public function preProcess()
    {
        if (count($this->dateColumns) > 0) {
            $this->data = $this->convertDatesToMysql($this->data, $this->dateColumns);
        }
    }

    public function postProcess()
    {
    }
    public function postHook()
    {
    }
    
    /**
     * Returns a value for an unparsable date column in the given row. Returns 
     * null by default, but subclasses can override this method to return any value
     * to set as the value for that date column.
     * @param string $columnName the name of the date column whose value is being
     * parsed. This can be any of the keys defined in this class' dateColumns array
     * whose current value cannot be parsed as a date by convertDatesToMysql
     * @param string $columnValue the value of the column
     * @param array $row the array of values of each column in a row
     */
    protected function valueForUnparsableDate($columnName, $columnValue, $row)
    {
        return null;
    }
}
