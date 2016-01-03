<?php namespace App\Import\OFAC\SDN;

class CitizenshipList extends Query {

	/**
	 * @var		string		$table
	 * @access	protected
	 */
	protected static $table = 'sdn_citizenship_list';

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
	 * @var		string		$country
	 * @access	public
	 */
	public $country;

	/**
	 * @var		string		$mainEntry
	 * @access	public
	 */
	public $mainEntry;


}