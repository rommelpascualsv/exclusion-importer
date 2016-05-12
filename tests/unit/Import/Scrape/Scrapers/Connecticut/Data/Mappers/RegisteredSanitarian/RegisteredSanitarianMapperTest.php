<?php

namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\RegisteredSanitarian;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\RegisteredSanitarian\RegisteredSanitarianMapper;
use Codeception\TestCase\Test;
use UnitTester;

class RegisteredSanitarianMapperTest extends Test
{

    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new RegisteredSanitarianMapper();
    }

    protected function _after()
    {
        
    }

    /**
     * To test that mapper's getDbData result will give as expected given a
     * prepared data input.
     */
    public function testGetDbData()
    {
        $data = [
            'LAST NAME' => 'ACCARDI',
            'FIRST NAME' => 'PATRICK',
            'LICENSE NUMBER' => '393',
            'STATUS' => 'ACTIVE',
            'ISSUE DATE' => '07/11/1985',
            'EXPIRATION DATE' => '06/30/2016',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => 'PATRICK',
            'last_name' => 'ACCARDI',
            'business_name' => null,
            'address1' => null,
            'address2' => null,
            'city' => null,
            'county' => null,
            'state' => null,
            'zip' => null,
            'license_no' => '393',
            'license_effective_date' => '1985-07-11',
            'license_expiration_date' => '2016-06-30',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];

        $this->assertSame($expectedDbData, $dbData);
    }

}
