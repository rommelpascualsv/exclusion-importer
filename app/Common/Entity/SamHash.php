<?php namespace App\Common\Entity;

class SamHash {


	/**
	 * @var string	$hash
	 */
	private $hash;


	/**
	 * @var array
	 */
	private $hashFields = [ 'SAM_Number', 'Excluding_Agency', 'Active_Date' ];


	/**
	 * Constructor
	 *
	 * @param	array	$samRow
	 * @throws	\InvalidArgumentException
	 */
	public function __construct(array $samRow)
	{
		// Get the keys from the passed array
		$passedKeys = array_keys($samRow);

		// Filter the keys to only those in the $hashFields array
		$validKeys = array_filter($passedKeys, function($key) {
			return (in_array($key, $this->hashFields));
		});

		// Compare the filtered array to the $hashFields array
		if (count($validKeys) != count($this->hashFields))
			throw new \InvalidArgumentException('SamHash requires at least 3 keys - [ SAM_Number, Excluding_Agency, Active_Date ]');

		// Set the fields to be hashed
		$samNumber = $samRow['SAM_Number'];

		$excludingAgency = $samRow['Excluding_Agency'];

		$activeDate = strtotime($samRow['Active_Date']);

		// Assign the hash
		$this->hash = md5(strtolower($samNumber.$excludingAgency.$activeDate));
	}


	/**
	 * toString Magic Method
	 *
	 * @return	string
	 */
	public function __toString()
	{
		return strtoupper($this->hash);
	}


}