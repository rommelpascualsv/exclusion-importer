<?php namespace App\Import\OFAC\SDN;

class VesselInfo extends Query 
{
	/**
	 * @var		string		$table
	 * @access	protected
	 */
	protected static $table = 'sdn_vessel_info';

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
	 * @var		string		$callSign
	 * @access	public
	 */
	public $callSign;

	/**
	 * @var		string		$vesselType
	 * @access	public
	 */
	public $vesselType;

	/**
	 * @var		string		$vesselInfo
	 * @access	public
	 */
	public $vesselInfo;

	/**
	 * @var		string		$vesselOwner
	 * @access	public
	 */
	public $vesselOwner;

	/**
	 * @var		int		$tonnage
	 * @access	public
	 */
	public $tonnage;

	/**
	 * @var		int		$grossRegisteredTonnage
	 */
	public $grossRegisteredTonnage;
}
