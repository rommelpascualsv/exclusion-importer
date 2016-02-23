<?php

namespace App\Import\Lists\HealthMil;

class HealthMilModel
{
    private $dateExcluded;

    private $term;

    private $exclusionDate;

    private $companies;

    private $firstName;

    private $middleName;

    private $lastName;

    private $addresses;

    private $summary;

    public function setDateExcluded($dateExcluded)
    {
        $date = new \DateTime($dateExcluded);
        $this->dateExcluded = $date->format('Y-m-d');
    }

    public function getDateExcluded()
    {
        return $this->dateExcluded;
    }

    public function setTerm($term)
    {
        $this->term = $term;
    }

    public function getTerm()
    {
        return $this->term;
    }

    public function setExclusionDate($term, $exclusionDate)
    {
        if (($timestamp = strtotime($term)) === false) {
            return;
        } else {
            $date = new \DateTime($exclusionDate);
            $date->modify($term);
            $this->exclusionDate = $date->format('Y-m-d');
        }
    }

    public function getExclusionDate()
    {
        return $this->exclusionDate;
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

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function setNames($people)
    {
        // Ignore all commas after the first.
        $value = explode(',', $people);
        if (is_array($value) && isset($value[0])) {
            $value = $value[0];
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
            $array[$property->getName()] = $property->getValue($this);
            $property->setAccessible(false);
        }

        return $array;
    }
}
