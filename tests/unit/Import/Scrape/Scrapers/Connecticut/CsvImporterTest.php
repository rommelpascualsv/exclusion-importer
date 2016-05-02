<?php
namespace Import\Scrape\Scrapers\Connecticut;


use App\Import\Scrape\Scrapers\Connecticut\CsvImporter;

class CsvImporterTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
	
    /**
     * @var CsvImporter
     */
    protected $importer;
    
    protected function _before()
	{
    	$this->db = app('db')->connection();
    	$this->importer = new CsvImporter($this->db);
    }

    protected function _after()
    {
    }
	
    public function testDbInsertFacility()
    {
    	$name = 'SAINT FRANCIS GI ENDOSCOPY, LLC';
    	$timestamp = '2016-05-01 01:30:00';
    	
    	$this->importer->dbInsertFacility($name, $timestamp);
    	
    	$this->tester->seeRecord('ct_roster_facilities', [
    			'name' => 'SAINT FRANCIS GI ENDOSCOPY, LLC',
    			'created_at' => '2016-05-01 01:30:00',
    			'updated_at' => '2016-05-01 01:30:00' 
    	]);
    }
    
    public function testImport()
    {
    	$this->importer->import();
    }
}