<?php namespace App\Import\OFAC\SDN;

class Entry extends Query {

	/**
	 * The table that stores the main OFAC SDN Entries
	 *
	 * @var		string		$_main_table
	 * @access	protected
	 */
	protected static $table = 'sdn_entries';

	/**
	 * The PRIMARY KEY in the Streamline Verify database table
	 *
	 * @var		int		$id
	 * @access	public
	 */
	public $id;

	/**
	 * OFAC SDN contributed unique id
	 *
	 * @var		int		$uid
	 * @access	public
	 */
	public $uid;

	/**
	 * @var		string	$firstName
	 * @access	public
	 */
	public $firstName;

	/**
	 * @var		string	$lastName
	 * @access	public
	 */
	public $lastName;

	/**
	 * @var		string	$title
	 * @access	public
	 */
	public $title;

	/**
	 * @var		string	$sdnType
	 * @access	public
	 */
	public $sdnType;

	/**
	 * @var		string	$remarks
	 * @access	public
	 */
	public $remarks;

	/**
	 * @var		ProgramList		$programList
	 * @access	public
	 */
	public $programList;

	/**
	 * A set of ID List Objects with an sdn_entry_id matching the Entry objects uid
	 *
	 * @var		IDList
	 */
	public $idList;

	/**
	 * A set of AKA List Objects with an sdn_entry_id matching the Entry objects uid
	 *
	 * @var		AKAList			$aka
	 */
	public $akaList;

	/**
	 * @var		AddressList		$addressList
	 * @access	public
	 */
	public $addressList;

	/**
	 * @var		NationalityList		$nationality_list
	 * @access	public
	 */
	public $nationality_list;

	/**
	 * @var		CitizenshipList	$citizenshipList
	 * @access	public
	 */
	public $citizenshipList;

	/**
	 * @var		DateOfBirthList	$dateOfBirthList
	 * @access	public
	 */
	public $dateOfBirthList;

	/**
	 * @var		PlaceOfBirthList	$placeOfBirthList
	 * @access	public
	 */
	public $placeOfBirthList;

	/**
	 * @var		VesselInfo		$vesselInfo
	 * @access	public
	 */
	public $vesselInfo;

	/**
	 * Get a data set of a single sdn entry by uid
	 *
	 * @param 	int		$uid
	 * @return	static
	 *
	 *
	public static function get_by_uid( $uid )
	{
		$me = self::get_by_uid( $uid );

		$me->program_list = ProgramList::get_by_sdn_entry_id( $uid );

		$me->aka_list = AKAList::get_by_uid( $uid );


		return $me;
	}
	*/

	/**
	 * @return $this
	 */
	public function load_entry()
	{
		if ( $this->uid == NULL )
		{
			return $this;
		}

		$sdnEntryId = $this->uid;

		$this->programList = ProgramList::getBySdnEntryId( $sdnEntryId );

		$this->idList = IDList::getBySdnEntryId( $sdnEntryId );

		$this->akaList = AKAList::getBySdnEntryId( $sdnEntryId );

		$this->addressList = AddressList::getBySdnEntryId( $sdnEntryId );

		$this->nationality_list = NationalityList::getBySdnEntryId( $sdnEntryId );

		$this->citizenshipList = CitizenshipList::getBySdnEntryId( $sdnEntryId );

		$this->dateOfBirthList = DateOfBirthList::getBySdnEntryId( $sdnEntryId );

		$this->placeOfBirthList = PlaceOfBirthList::getBySdnEntryId( $sdnEntryId );

		$this->vesselInfo = VesselInfo::getBySdnEntryId( $sdnEntryId );


	}

	public static function getBySdnEntryId($sdnEntryId)
	{
		return NULL;
	}



}