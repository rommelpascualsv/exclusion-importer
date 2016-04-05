<?php namespace App\Import\Lists;

use SimpleXMLElement;

class UnSanctionsIndividuals extends ExclusionList
{
    public $dbPrefix = 'unsancindividuals';

    public $uri = 'https://www.un.org/sc/suborg/sites/www.un.org.sc.suborg/files/consolidated.xml';

    public $type = 'xml';

    public $shouldHashListName = true;

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset'    => 0
    ];

    public $nodes = [
        'title'   => 'INDIVIDUALS',
        'subject' => 'INDIVIDUAL'
    ];

    public $fieldNames = [
        'dataid',
        'first_name',
        'second_name',
        'third_name',
        'fourth_name',
        'un_list_type',
        'reference_number',
        'listed_on',
        'nationality',
        'nationality2',
        'designation',
        'last_updated',
        'alias',
        'address',
        'date_of_birth',
        'place_of_birth',
        'comments',
    ];

    public $hashColumns = [
        'dataid',
        'first_name',
        'second_name',
        'third_name',
        'fourth_name',
        'listed_on',
        'last_updated',
        'alias',
        'date_of_birth',
    ];

    public $nodeMap = [
        'DATAID',
        'FIRST_NAME',
        'SECOND_NAME',
        'THIRD_NAME',
        'FOURTH_NAME',
        'UN_LIST_TYPE',
        'REFERENCE_NUMBER',
        'LISTED_ON',
        ['generateIndividualNationality'],
        'NATIONALITY2',
        ['generateIndividualDesignation'],
        'SORT_KEY_LAST_MOD',
        ['generateIndividualAlias'],
        ['generateIndividualAddress'],
        ['generateIndividualDateOfBirth'],
        ['generateIndividualPlaceOfBirth'],
        'COMMENTS1'
    ];

    /**
     * Generate Nationality for Individual
     * @param $node SimpleXMLElement
     * @return array
     */
    public function generateIndividualNationality($node)
    {
        $result = [];

        if ($node->NATIONALITY->VALUE) {
            foreach ($node->NATIONALITY->VALUE as $item) {
                $result[] = $this->prepareItem($item);
            }
        }

        return implode('; ', $result);
    }

    /**
     * Generate Designation for Individual
     * @param $node SimpleXMLElement
     * @return array
     */
    public function generateIndividualDesignation($node)
    {
        $result = [];

        if ($node->DESIGNATION->VALUE) {
            foreach ($node->DESIGNATION->VALUE as $item) {
                $result[] = $this->prepareItem($item);
            }
        }

        return implode('; ', $result);
    }

    /**
     * Generate Alias for Individual
     * @param $node SimpleXMLElement
     * @return array
     */
    public function generateIndividualAlias($node)
    {
        $result = [];

        if ($node->INDIVIDUAL_ALIAS) {
            foreach ($node->INDIVIDUAL_ALIAS as $item) {
                $result[] = $this->prepareItem($item->ALIAS_NAME);
            }
        }

        return implode('; ', $result);
    }

    /**
     * Generate Address for Individual
     * @param $node SimpleXMLElement
     * @return array
     */
    public function generateIndividualAddress($node)
    {
        $result = [];

        if ($node->INDIVIDUAL_ADDRESS) {
            foreach ($node->INDIVIDUAL_ADDRESS as $item) {
                $address = [];
                if ($item->STREET) {
                    $address[] = $this->prepareItem($item->STREET);
                }
                if ($item->CITY) {
                    $address[] = $this->prepareItem($item->CITY);
                }
                if ($item->STATE_PROVINCE) {
                    $address[] = $this->prepareItem($item->STATE_PROVINCE);
                }
                if ($item->ZIP_CODE) {
                    $address[] = $this->prepareItem($item->ZIP_CODE);
                }
                if ($item->COUNTRY) {
                    $address[] = $this->prepareItem($item->COUNTRY);
                }

                if ($address) {
                    $result[] = implode(', ', $address);
                }
            }
        }

        return implode('; ', $result);
    }

    /**
     * Generate Date of Birth for Individual
     * @param $node SimpleXMLElement
     * @return array
     */
    public function generateIndividualDateOfBirth($node)
    {
        $result = [];

        if ($node->INDIVIDUAL_DATE_OF_BIRTH) {
            foreach ($node->INDIVIDUAL_DATE_OF_BIRTH as $item) {
                $date = false;
                if ($item->TYPE_OF_DATE == 'Between') {
                    $date = $this->prepareItem($item->FROM_YEAR) . ' - ' . $this->prepareItem($item->TO_YEAR);
                } elseif ($item->DATE) {
                    $date = $this->prepareItem($item->DATE);
                } elseif ($item->YEAR) {
                    $date = $this->prepareItem($item->YEAR);
                }

                if ($date) {
                    $result[] = $date;
                }
            }
        }

        return implode('; ', $result);
    }

    /**
     * Generate Place of Birth for Individual
     * @param $node SimpleXMLElement
     * @return array
     */
    public function generateIndividualPlaceOfBirth($node)
    {
        $result = [];

        if ($node->INDIVIDUAL_PLACE_OF_BIRTH) {
            foreach ($node->INDIVIDUAL_PLACE_OF_BIRTH as $item) {
                $place = [];
                if ($item->CITY) {
                    $place[] = $this->prepareItem($item->CITY);
                }
                if ($item->STATE_PROVINCE) {
                    $place[] = $this->prepareItem($item->STATE_PROVINCE);
                }
                if ($item->COUNTRY) {
                    $place[] = $this->prepareItem($item->COUNTRY);
                }

                if ($place) {
                    $result[] = implode(', ', $place);
                }
            }
        }

        return implode('; ', $result);
    }

    private function prepareItem($item)
    {
        $item = (string) $item;
        $item = trim($item);

        return $item;
    }
}
