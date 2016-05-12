<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\Hospitals;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

/**
 * Description of Podiatrist
 *
 * @author Lenovo
 */
class ChildrensHospitalsMapper extends BaseMapper
{

    /**
     * Get db data
     * @param array $data
     * @return array
     */
    public function getDbData(array $data)
    {
        return $this->getRosterDbData([
                    'business_name' => $data['FACILITY NAME'],
                    'address1' => $data['ADDRESS'],
                    'city' => $data['CITY'],
                    'state' => $data['STATE'],
                    'zip' => $data['ZIP'],
                    'license_no' => $data['LICENSE NO.'],
                    'license_effective_date' => $this->getDateFromFormat($data['EFFECTIVE DATE']),
                    'license_expiration_date' => $this->getDateFromFormat($data['EXPIRATION DATE']),
                    'license_status' => $data['STATUS'],
        ]);
    }

}
