<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

class ClinicalSocialWorkerMapper extends BaseMapper
{

    protected $csvHeaders = [
        'LICENSE NO.',
        'FIRST NAME',
        'LAST NAME',
        'ADDRESS1',
        'ADDRESS2',
        'CITY',
        'STATE',
        'ZIP',
        'COUNTY',
        'STATUS',
        'REASON',
        'ISSUE DATE',
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
                    'license_no' => $data['LICENSE NO.'],
                    'first_name' => $data['FIRST NAME'],
                    'last_name' => $data['LAST NAME'],
                    'address1' => $data['ADDRESS1'],
                    'address2' => $data['ADDRESS2'],
                    'city' => $data['CITY'],
                    'state' => $data['STATE'],
                    'zip' => $data['ZIP'],
                    'county' => $data['COUNTY'],
                    'license_status' => $data['STATUS'],
                    'license_status_reason' => $data['REASON'],
                    'license_effective_date' => $this->getDateFromFormat($data['ISSUE DATE']),
                    'license_expiration_date' => $this->getDateFromFormat($data['EXPIRATION DATE'])
        ]);
    }

}
