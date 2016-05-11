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
     * @var array
     */
    public $ignoreColumns = [];

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

    protected $npiColumnName = null;

    protected $providerNumberColumnName = null;

    private $npiColumnIndex = null;

    private $providerNumberColumnIndex = null;

    public function __construct()
    {
        // Sets the corresponding npi column index if the npi column name property is not null/empty
        if ($this->npiColumnName) {
            // search the field name list for npi column name
            $this->npiColumnIndex = array_search($this->npiColumnName, $this->fieldNames);
        }

        // Sets the corresponding provider number column index if the npi column name property is not null/empty
        if ($this->providerNumberColumnName) {
            // search the field name list for provider number column name
            $this->providerNumberColumnIndex = array_search($this->providerNumberColumnName, $this->fieldNames);
        }

        $this->retrieverFactory = new RetrieverFactory;
    }

    public function retrieveData()
    {
        $retriever = $this->retrieverFactory->make($this->type);
        $this->data = $retriever->retrieveData($this);
    }

    public function convertDatesToMysql($row, $dateColumns)
    {
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
    }

    public function removeColumns(array $row, $ignoreColumns)
    {
        foreach ($ignoreColumns as $index) {
            unset($row[$index]);
        }

        return array_values($row);
    }

    public function convertToAssoc($row)
    {
        return array_combine($this->fieldNames, $row);
    }

    public function preProcess()
    {
        $this->data = array_map(function ($row) {

            if (count($this->dateColumns) > 0) {
                $row = $this->convertDatesToMysql($row, $this->dateColumns);
            }

            if (count($this->ignoreColumns) > 0) {
                $row = $this->removeColumns($row, $this->ignoreColumns);
            }

            // Handle the npi value string if npi column name is not null/empty.
            if ($this->npiColumnName) {
                $npiColumnIndex = $this->getNpiColumnIndex();
                $row[$npiColumnIndex] = $this->handleNpiValues($row[$npiColumnIndex]);
            }

            $row = $this->convertToAssoc($row);

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
     * Getter method for $npiColumnIndex
     */
    protected function getNpiColumnIndex()
    {
        return $this->npiColumnIndex;
    }

    /**
     * Getter method for $providerNumberColumnIndex
     */
    protected function getProviderNumberColumnIndex()
    {
        return $this->providerNumberColumnIndex;
    }

    /**
     * Make a JSON array string representation for a given array, otherwise return a string value.
     *
     * @param array $npi the npi array
     * @return string the JSON array string representation or the string value
     */
    private function handleNpiValues(array $npi = null)
    {
        if (empty($npi)) {
            // return empty string if npi is null/empty
            return "";
        } elseif (count($npi) == 1) {
            // return as normal string if count is 1
            return head($npi);
        }

        // return the json encode string of the npi array
        return json_encode($npi);
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
