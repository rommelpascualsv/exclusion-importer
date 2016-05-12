<?php namespace App\Import\OFAC\SDN;

class AKAList extends Query 
{
	/**
	 * @var		string		$table
	 * @access	protected
	 */
	protected static $table = 'sdn_aka_list';

	/**
	 * @var		int		$id
	 * @access	public
	 */
	public $id;

	/**
	 * @var		int		$sdn_entry_id
	 * @access	public
	 */
	public $sdn_entry_id;

	/**
	 * @var		int		$uid
	 * @access	public
	 */
	public $uid;

	/**
	 * @var		string		$type
	 * @access	public
	 */
	public $type;

	/**
	 * @var		string		$type
	 * @access	public
	 */
	public $category;

	/**
	 * @var		string		$firstName
	 * @access	public
	 */
	public $lastName;

	/**
	 * @var		string		$lastName
	 * @access	public
	 */
	public $firstName;
}
