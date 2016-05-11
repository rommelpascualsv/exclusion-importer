<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\InfirmariesClinics;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

class FamilyPlanningClinicsMapper extends BaseMapper
{

    protected $csvHeaders = [
        'FACILITY NAME',
        'ADDRESS',
        'CITY',
        'STATE',
        'ZIP',
        'LICENSE NO.',
        'STATUS',
        'EFFECTIVE DATE',
        'EXPIRATION DATE',
    ];

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
