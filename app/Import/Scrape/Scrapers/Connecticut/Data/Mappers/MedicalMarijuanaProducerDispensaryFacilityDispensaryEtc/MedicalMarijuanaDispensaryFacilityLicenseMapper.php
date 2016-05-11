<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MedicalMarijuanaProducerDispensaryFacilityDispensaryEtc;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

class MedicalMarijuanaDispensaryFacilityLicenseMapper extends BaseMapper
{

    protected $csvHeaders = [
        'NAME',
        'ADDRESS',
        'CITY',
        'STATE',
        'ZIP',
        'LICENSE NUMBER',
        'STATUS',
        'EFFECTIVE DATE',
        'EXPIRATION DATE'
    ];

    /**
     * Get db data
     * @param array $data
     * @return array
     */
    public function getDbData(array $data)
    {
        return $this->getRosterDbData([
            'business_name' => $data['NAME'],
            'address1' => $data['ADDRESS'],
            'city' => $data['CITY'],
            'state' => $data['STATE'],
            'zip' => $data['ZIP'],
            'license_no' => $data['LICENSE NUMBER'],
            'license_effective_date' => $this->getDateFromFormat($data['EFFECTIVE DATE']),
            'license_expiration_date' => $this->getDateFromFormat($data['EXPIRATION DATE']),
            'license_status' => $data['STATUS']
        ]);
    }

}
