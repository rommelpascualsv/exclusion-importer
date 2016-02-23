<?php namespace App\Import\Lists;


use App\Import\Service\Exclusions\RetrieverFactory;

abstract class ExclusionList
{

	/**
	 * Database table's prefix (e.g. {$dbPrefix}_records)
	 *
	 * @var	string
	 */
	public $dbPrefix;


	/**
	 * File's public uri
	 *
	 * @var	string
	 */
	public $uri;


	/**
	 * @var	array
	 */
	public $retrieveOptions = [];


	/**
	 * @var	array
	 */
	public $headerOptions = [];


	/**
	 * @var	array
	 */
	public $data = [];


	/**
	 * @var
	 */
	public $fileHeaders;


	/**
	 * Columns to create a hash from
	 *
	 * @var	array
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
        return array_map(function($row) use ($dateColumns) {

            foreach ($dateColumns as $index)
            {
                if (strtotime($row[$index]))
                {
                    $date = new \DateTime($row[$index]);
                    $row[$index] = $date->format('Y-m-d');
                }
                else
                {
                    $row[$index] = null;
                }
            }

            return $row;

        }, $data);
    }

    public function preProcess()
    {
        if (count($this->dateColumns) > 0)
        {
            $this->data = $this->convertDatesToMysql($this->data, $this->dateColumns);
        }
    }

    public function postProcess() {}
    public function postHook() {}
}
