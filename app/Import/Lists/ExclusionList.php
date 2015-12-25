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
	public $retrieveOptions = array();


	/**
	 * @var	array
	 */
	public $headerOptions = array();


	/**
	 * @var	array
	 */
	public $data = array();


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
}
