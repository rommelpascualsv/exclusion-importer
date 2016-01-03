<?php namespace App\Import\OFAC\SDN;

class DateOfBirthList extends Query {

	/**
	 * @var		string		$table
	 * @access	protected
	 */
	protected static $table = 'sdn_date_of_birth_list';

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
	 * Date of birth given by the OFAC SDN\
	 * 	- Three possible formats:
	 * 		- 01 Jan 1979
	 * 		- Jan 1979
	 * 		- 1979
	 *
	 * @var		string		$dateOfBirth
	 * @access	public
	 */
	public $dateOfBirth;

	/**
	 * @var		bool		$mainEntry
	 * @access	public
	 */
	public $mainEntry;
}