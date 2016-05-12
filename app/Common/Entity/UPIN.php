<?php namespace App\Common\Entity;

/**
 * Class UPIN
 * @package SLV\Common\Entities
 */
class UPIN 
{
	/**
	 * @var	string	$value
	 */
	private $value;

	/**
	 * @param	string	$upin
	 * @throws	\InvalidArgumentException
	 */
	public function __construct($upin)
	{
		if ( ! preg_match('/^[A-Za-z0-9]{6}$/', trim($upin)))
			throw new \InvalidArgumentException('UPINs should be a 6 character alphanumeric string');

		$this->value = $upin;
	}

	/**
	 * @description
	 * To string Magic Method
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->value;
	}
}
