<?php
namespace Test\Unit;

use App\Import\Lists\HashUtils;
use App\Import\Lists\Tennessee;
use App\Import\ImportStats;
use App\Repositories\ExclusionListRecordRepository;
use CDM\Test\TestCase;
use Illuminate\Support\Facades\DB;

/**
 * Unit test for ExclusionLitRecordRepository. 
 * 
 * To run, execute the following in the shell : vendor/bin/codecept run unit Repositories/ExclusionListRecordRepositoryTest --debug
 *
 */
class ExclusionListRecordRepositoryTest extends TestCase
{
    private $repo;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->app->withFacades();
        
        $this->repo = new ExclusionListRecordRepository();
        
        $this->createTestingTables();
    }
    
    
    public function tearDown() {
        
        $this->dropTestingTables();
        
        parent::tearDown();
    }
    
    public function testGetImportStatsShouldIndicateAddedRecords()
    {
        $prodRecords = $this->addHashTo([
            ['last_name' => 'Foster', 'first_name' => 'Allen'    , 'middle_name' => 'R.', 'npi' => '1326132572', 'begin_date' => '2012-03-20', 'end_date' => null, 'reason' => 'Felony conviction'],
            ['last_name' => 'Butler', 'first_name' => 'Jonathan' , 'middle_name' => ''  , 'npi' => '1386753325', 'begin_date' => '2012-03-24', 'end_date' => null, 'reason' => 'Failure to respond to requests for records on Tenncare patients'],
            ['last_name' => 'Toles' , 'first_name' => 'Mechell'  , 'middle_name' => ''  , 'npi' => '1083785653', 'begin_date' => '2014-04-05', 'end_date' => null, 'reason' => 'Felony conviction']
        ]);
        
        // Prod
        DB::table('exclusion_list_repo_test_prod')->insert($prodRecords);
        
        // Staging (+1 new record)
        $stagingRecords = $this->addHashTo([
            ['last_name' => 'Foster', 'first_name' => 'Allen'    , 'middle_name' => 'R.', 'npi' => '1326132572', 'begin_date' => '2012-03-20', 'end_date' => null, 'reason' => 'Felony conviction'],
            ['last_name' => 'Butler', 'first_name' => 'Jonathan' , 'middle_name' => ''  , 'npi' => '1386753325', 'begin_date' => '2012-03-24', 'end_date' => null, 'reason' => 'Failure to respond to requests for records on Tenncare patients'],
            ['last_name' => 'Toles' , 'first_name' => 'Mechell'  , 'middle_name' => ''  , 'npi' => '1083785653', 'begin_date' => '2014-04-05', 'end_date' => null, 'reason' => 'Felony conviction'],
            ['last_name' => 'Dixon' , 'first_name' => 'Fredrico' , 'middle_name' => ''  , 'npi' => '1396820775', 'begin_date' => '2014-04-05', 'end_date' => null, 'reason' => 'Failure to respond to requests for records on Tenncare patients']
        ]);
        
        DB::table('exclusion_list_repo_test_staging')->insert($stagingRecords);
        
        $actual = $this->repo->getImportStats('tn1', 'exclusion_lists_cdm.exclusion_list_repo_test_staging', 'exclusion_lists_cdm.exclusion_list_repo_test_prod');
        
        $expected = (new ImportStats())->setAdded(1)->setDeleted(0)->setCurrentRecordCount(4)->setPreviousRecordCount(3);
        
        $this->assertEquals($expected, $actual);
    }
    
    public function testGetImportStatsShouldIndicateDeletedRecords()
    {
        $prodRecords = $this->addHashTo([
            ['last_name' => 'Foster', 'first_name' => 'Allen'    , 'middle_name' => 'R.', 'npi' => '1326132572', 'begin_date' => '2012-03-20', 'end_date' => null, 'reason' => 'Felony conviction'],
            ['last_name' => 'Butler', 'first_name' => 'Jonathan' , 'middle_name' => ''  , 'npi' => '1386753325', 'begin_date' => '2012-03-24', 'end_date' => null, 'reason' => 'Failure to respond to requests for records on Tenncare patients'],
            ['last_name' => 'Toles' , 'first_name' => 'Mechell'  , 'middle_name' => ''  , 'npi' => '1083785653', 'begin_date' => '2014-04-05', 'end_date' => null, 'reason' => 'Felony conviction']
        ]);
    
        // Prod
        DB::table('exclusion_list_repo_test_prod')->insert($prodRecords);
    
        // Staging (-1 record)
        $stagingRecords = $this->addHashTo([
            ['last_name' => 'Foster', 'first_name' => 'Allen'    , 'middle_name' => 'R.', 'npi' => '1326132572', 'begin_date' => '2012-03-20', 'end_date' => null, 'reason' => 'Felony conviction'],
            ['last_name' => 'Butler', 'first_name' => 'Jonathan' , 'middle_name' => ''  , 'npi' => '1386753325', 'begin_date' => '2012-03-24', 'end_date' => null, 'reason' => 'Failure to respond to requests for records on Tenncare patients'],
        ]);
    
        DB::table('exclusion_list_repo_test_staging')->insert($stagingRecords);
    
        $actual = $this->repo->getImportStats('tn1', 'exclusion_lists_cdm.exclusion_list_repo_test_staging', 'exclusion_lists_cdm.exclusion_list_repo_test_prod');
    
        $expected = (new ImportStats())->setAdded(0)->setDeleted(1)->setCurrentRecordCount(2)->setPreviousRecordCount(3);
    
        $this->assertEquals($expected, $actual);
    }
    
    public function testGetImportStatsShouldIndicateBothAddedAndDeletedRecords()
    {
        $prodRecords = $this->addHashTo([
            ['last_name' => 'Foster', 'first_name' => 'Allen'    , 'middle_name' => 'R.', 'npi' => '1326132572', 'begin_date' => '2012-03-20', 'end_date' => null, 'reason' => 'Felony conviction'],
            ['last_name' => 'Butler', 'first_name' => 'Jonathan' , 'middle_name' => ''  , 'npi' => '1386753325', 'begin_date' => '2012-03-24', 'end_date' => null, 'reason' => 'Failure to respond to requests for records on Tenncare patients'],
            ['last_name' => 'Toles' , 'first_name' => 'Mechell'  , 'middle_name' => ''  , 'npi' => '1083785653', 'begin_date' => '2014-04-05', 'end_date' => null, 'reason' => 'Felony conviction']
        ]);
    
        // Prod
        DB::table('exclusion_list_repo_test_prod')->insert($prodRecords);
    
        // Staging - ('Butler' modified to 'Butler Jr.' should count as one addition, one removal)
        $stagingRecords = $this->addHashTo([
            ['last_name' => 'Foster', 'first_name' => 'Allen'    , 'middle_name' => 'R.', 'npi' => '1326132572', 'begin_date' => '2012-03-20', 'end_date' => null, 'reason' => 'Felony conviction'],
            ['last_name' => 'Butler Jr.', 'first_name' => 'Jonathan' , 'middle_name' => ''  , 'npi' => '1386753325', 'begin_date' => '2012-03-24', 'end_date' => null, 'reason' => 'Failure to respond to requests for records on Tenncare patients'],
            ['last_name' => 'Toles' , 'first_name' => 'Mechell'  , 'middle_name' => ''  , 'npi' => '1083785653', 'begin_date' => '2014-04-05', 'end_date' => null, 'reason' => 'Felony conviction']
        ]);
    
        DB::table('exclusion_list_repo_test_staging')->insert($stagingRecords);
    
        $actual = $this->repo->getImportStats('tn1', 'exclusion_lists_cdm.exclusion_list_repo_test_staging', 'exclusion_lists_cdm.exclusion_list_repo_test_prod');
    
        $expected = (new ImportStats())->setAdded(1)->setDeleted(1)->setCurrentRecordCount(3)->setPreviousRecordCount(3);
    
        $this->assertEquals($expected, $actual);
    } 
    
    public function testGetImportStatsShouldCorrectlyCountDuplicateAdditions()
    {
        $prodRecords = $this->addHashTo([
            ['last_name' => 'Foster', 'first_name' => 'Allen'    , 'middle_name' => 'R.', 'npi' => '1326132572', 'begin_date' => '2012-03-20', 'end_date' => null, 'reason' => 'Felony conviction'],
            ['last_name' => 'Butler', 'first_name' => 'Jonathan' , 'middle_name' => ''  , 'npi' => '1386753325', 'begin_date' => '2012-03-24', 'end_date' => null, 'reason' => 'Failure to respond to requests for records on Tenncare patients'],
            ['last_name' => 'Toles' , 'first_name' => 'Mechell'  , 'middle_name' => ''  , 'npi' => '1083785653', 'begin_date' => '2014-04-05', 'end_date' => null, 'reason' => 'Felony conviction']
        ]);
    
        // Prod
        DB::table('exclusion_list_repo_test_prod')->insert($prodRecords);
    
        // Staging - ('Butler' modified to 'Butler Jr.' should count as one addition, one removal)
        $stagingRecords = $this->addHashTo([
            ['last_name' => 'Foster', 'first_name' => 'Allen'    , 'middle_name' => 'R.', 'npi' => '1326132572', 'begin_date' => '2012-03-20', 'end_date' => null, 'reason' => 'Felony conviction'],
            ['last_name' => 'Butler', 'first_name' => 'Jonathan' , 'middle_name' => ''  , 'npi' => '1386753325', 'begin_date' => '2012-03-24', 'end_date' => null, 'reason' => 'Failure to respond to requests for records on Tenncare patients'],
            ['last_name' => 'Toles' , 'first_name' => 'Mechell'  , 'middle_name' => ''  , 'npi' => '1083785653', 'begin_date' => '2014-04-05', 'end_date' => null, 'reason' => 'Felony conviction'],
            ['last_name' => 'Dixon' , 'first_name' => 'Fredrico' , 'middle_name' => ''  , 'npi' => '1396820775', 'begin_date' => '2014-04-05', 'end_date' => null, 'reason' => 'Failure to respond to requests for records on Tenncare patients'],
            ['last_name' => 'Dixon' , 'first_name' => 'Fredrico' , 'middle_name' => ''  , 'npi' => '1396820775', 'begin_date' => '2014-04-05', 'end_date' => null, 'reason' => 'Failure to respond to requests for records on Tenncare patients']
        ]);
    
        DB::table('exclusion_list_repo_test_staging')->insert($stagingRecords);
    
        $actual = $this->repo->getImportStats('tn1', 'exclusion_lists_cdm.exclusion_list_repo_test_staging', 'exclusion_lists_cdm.exclusion_list_repo_test_prod');
    
        $expected = (new ImportStats())->setAdded(2)->setDeleted(0)->setCurrentRecordCount(5)->setPreviousRecordCount(3);
    
        $this->assertEquals($expected, $actual);
    }    
    
    private function createTestingTables()
    {
        
        // Create test 'staging' table
        DB::statement('DROP TABLE IF EXISTS `exclusion_lists_cdm`.`exclusion_list_repo_test_staging`');
        DB::statement('CREATE TABLE `exclusion_lists_cdm`.`exclusion_list_repo_test_staging` LIKE `tn1_records`');
        
        // Create test 'prod' table
        DB::statement('DROP TABLE IF EXISTS `exclusion_lists_cdm`.`exclusion_list_repo_test_prod`');
        DB::statement('CREATE TABLE `exclusion_lists_cdm`.`exclusion_list_repo_test_prod` LIKE `tn1_records`');
        
    }  
    
    private function dropTestingTables()
    {
        // Drop test 'staging' table
        DB::statement('DROP TABLE IF EXISTS `exclusion_lists_cdm`.`exclusion_list_repo_test_staging`');
        
        // Drop test 'prod' table
        DB::statement('DROP TABLE IF EXISTS `exclusion_lists_cdm`.`exclusion_list_repo_test_prod`');
    }
    
    private function addHashTo($records)
    {
        $exclusionList = new Tennessee();
        
        $results = [];
        
        foreach ($records as $record)
        {
            $record['hash'] = hex2bin(HashUtils::generateHash($record, $exclusionList));
            $results[] = $record;
        }
        
        return $results;
    }
}
