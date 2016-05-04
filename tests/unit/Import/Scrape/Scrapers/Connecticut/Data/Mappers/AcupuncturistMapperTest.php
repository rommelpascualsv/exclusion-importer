<?php
namespace Import\Scrape\Scrapers\Connecticut\Data\Mappers;


use App\Import\Scrape\Scrapers\Connecticut\Data\Mappers\AcupuncturistMapper;
use App\Import\Scrape\Scrapers\Connecticut\CsvImporter;

class AcupuncturistMapperTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    	$this->mapper = new AcupuncturistMapper();
    }

    protected function _after()
    {
    }

// tests
    public function testGetCsvData()
    {
    	$data = [
    			'1',
    			'THOMAS',
    			'RYAN',
    			'15 MAIN STREET',
    			'',
    			'EAST HAMPTON',
    			'CT',
    			'O6424',
    			'Middlesex',
    			'ACTIVE',
    			'CURRENT',
    			'04/12/1996',
    			'08/31/2016',	 
    	];
    	 
    	$csvData = $this->mapper->getCsvData($data);
    	
    	$expectedCsvData = [
    			'license_no' => '1',
    			'first_name' => 'THOMAS',
    			'last_name' => 'RYAN',
    			'address1' => '15 MAIN STREET',
    			'address2' => '',
    			'city' => 'EAST HAMPTON',
    			'state' => 'CT',
    			'zip' => 'O6424',
    			'county' => 'Middlesex',
    			'status' => 'ACTIVE',
    			'reason' => 'CURRENT',
    			'issue_date' => '04/12/1996',
    			'expiration_date' => '08/31/2016',
    	];
    	 
    	$this->assertEquals($expectedCsvData, $csvData);
    }
    
    public function testGetDbData()
    {
    	$data = [
    			'license_no' => '1',
    			'first_name' => 'THOMAS',
    			'last_name' => 'RYAN',
    			'address1' => '15 MAIN STREET',
    			'address2' => '',
    			'city' => 'EAST HAMPTON',
    			'state' => 'CT',
    			'zip' => 'O6424',
    			'county' => 'Middlesex',
    			'status' => 'ACTIVE',
    			'reason' => 'CURRENT',
    			'issue_date' => '04/12/1996',
    			'expiration_date' => '08/31/2016',
    	];
    
    	$dbData = $this->mapper->getDbData($data);
    
    	$expectedDbData = [
    			'type' => CsvImporter::TYPE_PERSON,
    			'values' => [
    					'first_name' => 'THOMAS',
    					'last_name' => 'RYAN',
    					'address1' => '15 MAIN STREET',
    					'address2' => '',
    					'city' => 'EAST HAMPTON',
    					'county' => 'Middlesex',
    					'state_code' => 'CT',
    					'zip' => 'O6424',
    					'complete_address' => ''
    			]
    	];
    
    	$this->assertEquals($expectedDbData, $dbData);
    }
}