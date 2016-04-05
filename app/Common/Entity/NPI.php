<?php namespace App\Common\Entity;

/**
 * Class NPI
 * @package SLV\Common\Entities
 */
class NPI 
{

	/**
	 * @var	string	$value
	 */
	private $value;

	/**
	 * @param	string	$npi
	 * @throws	\InvalidArgumentException
	 */
	public function __construct($npi)
	{
		if ( ! preg_match('/^[1][0-9]{9}$/', trim($npi)))
			throw new \InvalidArgumentException('NPI should be a 10-digit number starting with 1');

		$this->value = $npi;
	}

	public function __toString()
	{
		return (string) $this->value;
	}
}
