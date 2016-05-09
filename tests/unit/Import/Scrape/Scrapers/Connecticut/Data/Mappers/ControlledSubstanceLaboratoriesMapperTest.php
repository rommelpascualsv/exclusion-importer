<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers;

use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\ControlledSubstanceLaboratoriesMapper;
use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\MapperInterface;
use App\Import\Scrape\Scrapers\Connecticut\CsvImporter;

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
    
    // tests
    public function testGetCsvData()
    {
        $data = [
            'ROBERT',
            'MALISON',
            '',
            '34 PARK ST',
            'NEW HAVEN',
            'CT',
            '06519-1109',
            'CSL.0000432',
            'ACTIVE',
            '02/01/2016',
            '01/31/2017'
        ];
        
        $csvData = $this->mapper->getCsvData($data);
        
        $expectedCsvData = [
            'contact_first_name' => 'ROBERT',
            'contact_last_name' => 'MALISON',
            'business_name' => '',
            'address' => '34 PARK ST',
            'city' => 'NEW HAVEN',
            'state' => 'CT',
            'zip' => '06519-1109',
            'license_number' => 'CSL.0000432',
            'status' => 'ACTIVE',
            'effective_date' => '02/01/2016',
            'expiration_date' => '01/31/2017'
        ];
        
        $this->assertEquals($expectedCsvData, $csvData);
    }
    
    public function testGetDbDataPerson()
    {
        $data = [
            'contact_first_name' => 'ROBERT',
            'contact_last_name' => 'MALISON',
            'business_name' => '',
            'address' => '34 PARK ST',
            'city' => 'NEW HAVEN',
            'state' => 'CT',
            'zip' => '06519-1109',
            'license_number' => 'CSL.0000432',
            'status' => 'ACTIVE',
            'effective_date' => '02/01/2016',
            'expiration_date' => '01/31/2017'
        ];
        
        $dbData = $this->mapper->getDbData($data);
        
        $expectedDbData = [
            'first_name' => 'ROBERT',
            'last_name' => 'MALISON',
            'business_name' => '',
            'address1' => '34 PARK ST',
            'address2' => '',
            'city' => 'NEW HAVEN',
            'county' => '',
            'state' => 'CT',
            'zip' => '06519-1109',
            'license_no' => 'CSL.0000432',
            'license_effective_date' => '2016-02-01',
            'license_expiration_date' => '2017-01-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => ''
        ];
        
        $this->assertEquals($expectedDbData, $dbData);
    }
    
    public function testGetDbDataFacility()
    {
        $data = [
            'contact_first_name' => '',
            'contact_last_name' => '',
            'business_name' => 'NARCOTICS CONTROL OFFICER',
            'address' => '5 RESEARCH PKWY',
            'city' => 'WALLINGFORD',
            'state' => 'CT',
            'zip' => '06492-1951',
            'license_number' => 'CSL.0000252',
            'status' => 'ACTIVE',
            'effective_date' => '02/01/2016',
            'expiration_date' => '01/31/2017'
        ];
    
        $dbData = $this->mapper->getDbData($data);
    
        $expectedDbData = [
            'first_name' => '',
            'last_name' => '',
            'business_name' => 'NARCOTICS CONTROL OFFICER',
            'address1' => '5 RESEARCH PKWY',
            'address2' => '',
            'city' => 'WALLINGFORD',
            'county' => '',
            'state' => 'CT',
            'zip' => '06492-1951',
            'license_no' => 'CSL.0000252',
            'license_effective_date' => '2016-02-01',
            'license_expiration_date' => '2017-01-31',
            'license_status' => 'ACTIVE',
            'license_status_reason' => ''
        ];
    
        $this->assertEquals($expectedDbData, $dbData);
    }
}