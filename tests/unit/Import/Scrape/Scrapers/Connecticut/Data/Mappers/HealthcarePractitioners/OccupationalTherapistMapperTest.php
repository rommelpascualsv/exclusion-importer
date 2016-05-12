<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\HealthcarePractitioners\OccupationalTherapistMapper;
use Codeception\TestCase\Test;
use UnitTester;


class OccupationalTherapistMapperTest extends Test
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new OccupationalTherapistMapper();
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
            'LICENSE' => '1007',
            'FIRST NAME' => 'JANE',
            'LAST NAME' => 'ELLIOTT',
            'ADDRESS1' => '2 GREENWOOD AVENUE',
            'ADDRESS2' => '',
            'CITY' => 'BETHEL',
            'STATE' => 'CT',
            'ZIP' => 'O68O1',
            'COUNTY' => 'Fairfield',
            'STATUS' => 'ACTIVE',
            'REASON' => 'CURRENT',
            'EFFECTIVE DATE' => '08/01/2015',
            'EXPIRATION DATE' => '07/31/2017'
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => 'JANE',
            'last_name' => 'ELLIOTT',
            'business_name' => null,
            'address1' => '2 GREENWOOD AVENUE',
            'address2' => null,
            'city' => 'BETHEL',
            'county' => 'Fairfield',
            'state' => 'CT',
            'zip' => 'O68O1',
            'license_no' => '1007',
            'license_effective_date' => '2015-08-01',
            'license_expiration_date' => '2017-07-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => 'CURRENT'
        ];

        $this->assertSame($expectedDbData, $dbData);
    }
}