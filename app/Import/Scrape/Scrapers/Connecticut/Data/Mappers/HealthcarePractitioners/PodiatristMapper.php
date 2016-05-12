<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

/**
 * Description of Podiatrist
 *
 * @author Lenovo
 */
class PodiatristMapper extends BaseMapper
{

    /**
     * Get db data
     * @param array $data
     * @return array
     */
    public function getDbData(array $data)
    {
        return $this->getRosterDbData([
                    'first_name' => $data['FIRST NAME'],
                    'last_name' => $data['LAST NAME'],
                    'address1' => $data['ADDRESS1'],
                    'address2' => $data['ADDRESS2'],
                    'city' => $data['CITY'],
                    'county' => $data['COUNTY'],
                    'state' => $data['STATE'],
                    'zip' => $data['ZIP'],
                    'license_no' => $data['LICENSE NO.'],
                    'license_effective_date' => $this->getDateFromFormat($data['ISSUE DATE']),
                    'license_expiration_date' => $this->getDateFromFormat($data['EXPIRATION DATE']),
                    'license_status' => $data['STATUS'],
                    'license_status_reason' => $data['REASON']
        ]);
    }

}
