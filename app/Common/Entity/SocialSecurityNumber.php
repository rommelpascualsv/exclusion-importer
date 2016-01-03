<?php namespace App\Common\Entity;


class SocialSecurityNumber {


    private $pattern = '/^(\d{3}-\d{2}-\d{4})|(\d{3}\d{2}\d{4})$/';


    private $socialSecurityNumber;


    public function __construct($ssn)
    {

        if ( ! preg_match($this->pattern, trim($ssn)))
        {
            throw new \InvalidArgumentException('Invalid Social Security Number.');
        }

        $this->socialSecurityNumber = trim($ssn);
    }

    public function lastFour()
    {
        return substr($this->socialSecurityNumber, -4);
    }


    public function __toString()
    {
        return (string) $this->socialSecurityNumber;
    }

}
