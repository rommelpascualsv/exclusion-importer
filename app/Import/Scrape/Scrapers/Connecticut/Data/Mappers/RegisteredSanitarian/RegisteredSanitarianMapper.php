<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\RegisteredSanitarian;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\BaseMapper;

class RegisteredSanitarianMapper extends BaseMapper
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
                    'license_no' => $data['LICENSE NUMBER'],
                    'license_effective_date' => $this->getDateFromFormat($data['ISSUE DATE']),
                    'license_expiration_date' => $this->getDateFromFormat($data['EXPIRATION DATE']),
                    'license_status' => $data['STATUS']
        ]);
    }

}
