<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MentalHealthCare\ClinicalSocialWorkerMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperFactory;
use Codeception\TestCase\Test;
use UnitTester;


class ClinicalSocialWorkerMapperTest extends Test
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->mapper = new ClinicalSocialWorkerMapper();
    }

    protected function _after()
    {
    }

    // tests
    public function testGetCsvData()
    {
        $data = [
            '000000',
            'KERRYANN',
            'FRANK',
            '24 Tierney Road',
            '',
            'Hamden',
            'CT',
            'O6514',
            '',
            'ACTIVE',
            'RENEWAL APPLICATION SENT',
            '06/15/2015',
            '05/31/2016',
        ];

        $actual = $this->mapper->getCsvData($data);
        $expected = [
            'LICENSE NO.' => '000000',
            'FIRST NAME' => 'KERRYANN',
            'LAST NAME' => 'FRANK',
            'ADDRESS1' => '24 Tierney Road',
            'ADDRESS2' => '',
            'CITY' => 'Hamden',
            'STATE' => 'CT',
            'ZIP' => 'O6514',
            'COUNTY' => '',
            'STATUS' => 'ACTIVE',
            'REASON' => 'RENEWAL APPLICATION SENT',
            'ISSUE DATE' => '06/15/2015',
            'EXPIRATION DATE' => '05/31/2016',
        ];

        $this->assertSame($expected, $actual);
    }

    /**
     * To test that mapper's getDbData result will give as expected given a
     * prepared data input.
     */
    public function testGetDbData()
    {
        $data = [
            'LICENSE NO.' => '000000',
            'FIRST NAME' => 'KERRYANN',
            'LAST NAME' => 'FRANK',
            'ADDRESS1' => '24 Tierney Road',
            'ADDRESS2' => '',
            'CITY' => 'Hamden',
            'STATE' => 'CT',
            'ZIP' => 'O6514',
            'COUNTY' => '',
            'STATUS' => 'ACTIVE',
            'REASON' => 'RENEWAL APPLICATION SENT',
            'ISSUE DATE' => '06/15/2015',
            'EXPIRATION DATE' => '05/31/2016',
        ];

        $dbData = $this->mapper->getDbData($data);

        $expectedDbData = [
            'first_name' => 'KERRYANN',
            'last_name' => 'FRANK',
            'business_name' => null,
            'address1' => '24 Tierney Road',
            'address2' => null,
            'city' => 'Hamden',
            'county' => null,
            'state' => 'CT',
            'zip' => 'O6514',
            'license_no' => '000000',
            'license_effective_date' => '2015-06-15',
            'license_expiration_date' => '2016-05-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => 'RENEWAL APPLICATION SENT'
        ];

        $this->assertSame($expectedDbData, $dbData);
    }
    
    public function testMapperFactoryInstance()
    {
        $mapper = MapperFactory::createByKeys('mental_health_care', 'clinical_social_worker');
        
        $this->assertInstanceOf(ClinicalSocialWorkerMapper::class, $mapper);
    }
}