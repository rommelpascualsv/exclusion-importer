<?php namespace App\Import\Lists;


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


	public $shouldHashListName = false;


	public $type;


	public function preProcess($data) {

        return $data;
	}


	public function postProcess($data) {

		return $data;
	}


	public $nodes = [];


	public $nodeMap = [];

}
