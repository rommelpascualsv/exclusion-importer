<?php namespace App\Import\Lists;

use SimpleXMLElement;

class UnSanctionsEntities extends ExclusionList
{
    public $dbPrefix = 'unsancentities';

    public $uri = 'https://www.un.org/sc/suborg/sites/www.un.org.sc.suborg/files/consolidated.xml';

    public $type = 'xml';

    public $shouldHashListName = true;

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset'    => 0
    ];

    public $nodes = [
        'title'   => 'ENTITIES',
        'subject' => 'ENTITY'
    ];

    public $fieldNames = [
        'dataid',
        'entity_name',
        'un_list_type',
        'reference_number',
        'listed_on',
        'submitted_on',
        'comments',
        'last_updated',
        'entity_alias',
        'entity_address',
    ];

    public $hashColumns = [
        'dataid',
        'entity_name',
        'listed_on',
        'entity_alias',
    ];

    public $nodeMap = [
        'DATAID',
        'FIRST_NAME',
        'UN_LIST_TYPE',
        'REFERENCE_NUMBER',
        'LISTED_ON',
        'SUBMITTED_ON',
        'COMMENTS1',
        ['generateEntityLastUpdated'],
        ['generateEntityAlias'],
        ['generateEntityAddress'],
    ];

    /**
     * Generate Alias for Entity
     * @param $node SimpleXMLElement
     * @return array
     */
    public function generateEntityAlias($node)
    {
        $result = [];

        if ($node->ENTITY_ALIAS) {
            foreach ($node->ENTITY_ALIAS as $item) {
                $result[] = $this->prepareItem($item->ALIAS_NAME);
            }
        }

        return implode('; ', $result);
    }

    /**
     * Generate Address for Entity
     * @param $node SimpleXMLElement
     * @return array
     */
    public function generateEntityAddress($node)
    {
        $result = [];

        if ($node->ENTITY_ADDRESS) {
            foreach ($node->ENTITY_ADDRESS as $item) {
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
     * Generate last_updated for Entity
     * @param $node SimpleXMLElement
     * @return array
     */
    public function generateEntityLastUpdated($node)
    {
        if ($node->LAST_DAY_UPDATED) {
            $value = (! empty($node->LAST_DAY_UPDATED->VALUE) ? $node->LAST_DAY_UPDATED->VALUE[count($node->LAST_DAY_UPDATED->VALUE)-1] : '' );
            return $this->prepareItem($value);
        }

        return '';
    }

    private function prepareItem($item)
    {
        $item = (string) $item;
        $item = trim($item);

        return $item;
    }
}
