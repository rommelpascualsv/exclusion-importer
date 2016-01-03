<?php namespace App\Import\OFAC\SDN;

class IDList extends Query {

	/**
	 * @var		string		$table
	 * @access	protected
	 */
	protected static $table = 'sdn_id_list';

	/**
	 * @var		int			$id
	 * @access	public
	 */
	public $id;

	/**
	 * @var		int			$sdn_entry_id
	 * @access	public
	 */
	public $sdn_entry_id;

	/**
	 * @var		int			$uid
	 * @access	public
	 */
	public $uid;

	/**
	 * @var		string		$idType
	 * @access	public
	 */
	public $idType;

	/**
	 * @var		string		$idNumber
	 * @access	public
	 */
	public $idNumber;

	/**
	 * @var		string		$idCountry
	 * @access	public
	 */
	public $idCountry;

	/**
	 * @var		string		$issueDate
	 * @access	public
	 */
	public $issueDate;

	/**
	 * @var		string		$expirationDate
	 * @access	public
	 */
	public $expirationDate;
}