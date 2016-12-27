<?php

namespace App\Import\Lists\WashingtonDC;

class WashingtonDCModel
{
    private $actionDate;

    private $terminationDate;

    private $companies;

    private $firstName;

    private $middleName;

    private $lastName;

    private $addresses;

    private $principals;

    const HEADER_MAP = [
        'companies' => 'company_name',
        'firstName' => 'first_name',
        'middleName' => 'middle_name',
        'lastName' => 'last_name',
        'addresses' => 'address',
        'principals' => 'principals',
        'actionDate' => 'action_date',
        'terminationDate' => 'termination_date'
    ];

    public function setActionDate($actionDate)
    {
        $this->actionDate = $actionDate;
    }

    public function getActionDate()
    {
        return $this->actionDate;
    }

    public function setPrincipals($principals)
    {
        $this->principals = $principals;
    }

    public function getPrincipals()
    {
        return $this->principals;
    }

    public function getTerminationDate()
    {
        return $this->terminationDate;
    }

    public function setTerminationDate($terminationDate)
    {
        $date = new \DateTime($terminationDate);
        $this->terminationDate = $date->format('Y-m-d');
    }

    public function setCompanies($companies)
    {
        $this->companies = $companies;
    }

    public function getCompanies()
    {
        return $this->companies;
    }

    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
    }

    public function getAddresses()
    {
        return $this->addresses;
    }

    public function setNames($people)
    {
        // Ignore all commas after the first.
        $name = explode(',', $people);
        if (is_array($name) && isset($name[0])) {
            $value = $name[0];
            if (isset($name[1])) {
            	$this->title = $name[1];
            }
        }

        // Space separated first and middle and last name.
        $value = explode(' ', $value);
        if (is_string($value)) {
            $this->firstName = $value;

            return;
        }

        $len = count($value);
        switch ($len) {
            case 2:
                $this->firstName = $value[0];
                $this->lastName = $value[1];
                break;
            case 3:
                $this->firstName = $value[0];
                $this->middleName = $value[1];
                $this->lastName = $value[2];
                break;
            case 4:
                $this->firstName = $value[0];
                $this->middleName = $value[1] . ' ' . $value[2];
                $this->lastName = $value[3];
                break;
            default:
                $this->firstName = $value[0];
                break;
        }
    }
    
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getMiddleName()
    {
        return $this->middleName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function toArray()
    {
        $reflectionClass = new \ReflectionClass(get_class($this));
        $array = array();
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[self::HEADER_MAP[$property->getName()]] = $property->getValue($this);
            $property->setAccessible(false);
        }

        return $array;
    }
}
