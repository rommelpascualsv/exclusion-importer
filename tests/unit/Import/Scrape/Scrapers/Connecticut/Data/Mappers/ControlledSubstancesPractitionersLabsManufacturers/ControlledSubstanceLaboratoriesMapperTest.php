<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperInterface;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstancesPractitionersLabsManufacturers\ControlledSubstanceLaboratoriesMapper;

class ControlledSubstanceLaboratoriesMapperTest extends \Codeception\TestCase\Test
{

    /**
     *
     * @var \UnitTester
     */
    protected $tester;
    
    /** 
     * @var MapperInterface
     */
    protected $mapper;
    
    protected function _before()
    {
        $this->mapper = new ControlledSubstanceLaboratoriesMapper();
    }

    protected function _after()
    {}
    
    /**
     * To test that mapper's getDbData result will give as expected given a
     * prepared data input.
     */
    public function testGetDbData()
    {
        $data = [
            'CONTACT FIRST NAME' => 'ROBERT',
            'CONTACT LAST NAME' => 'MALISON',
            'BUSINESS NAME' => '',
            'ADDRESS' => '34 PARK ST',
            'CITY' => 'NEW HAVEN',
            'STATE' => 'CT',
            'ZIP' => '06519-1109',
            'LICENSE NUMBER' => 'CSL.0000432',
            'STATUS' => 'ACTIVE',
            'EFFECTIVE DATE' => '02/01/2016',
            'EXPIRATION DATE' => '01/31/2017'
        ];
        
        $dbData = $this->mapper->getDbData($data);
        
        $expectedDbData = [
            'first_name' => 'ROBERT',
            'last_name' => 'MALISON',
            'business_name' => null,
            'address1' => '34 PARK ST',
            'address2' => null,
            'city' => 'NEW HAVEN',
            'county' => null,
            'state' => 'CT',
            'zip' => '06519-1109',
            'license_no' => 'CSL.0000432',
            'license_effective_date' => '2016-02-01',
            'license_expiration_date' => '2017-01-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => null
        ];
        
        $this->assertSame($expectedDbData, $dbData);
    }
}