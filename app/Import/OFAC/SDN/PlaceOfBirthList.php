<?php namespace App\Import\OFAC\SDN;

class PlaceOfBirthList extends Query 
{
	/**
	 * @var		string		$table
	 * @access	protected
	 */
	protected static $table = 'sdn_place_of_birth_list';

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
	 * @var		string		$placeOfBirth
	 * @access	public
	 */
	public $placeOfBirth;

	/**
	 * @var		string		$mainEntry
	 * @access	public
	 */
	public $mainEntry;
}
