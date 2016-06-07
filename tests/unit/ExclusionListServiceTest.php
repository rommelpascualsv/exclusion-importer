<?php

namespace Test\Unit;

use App\Import\Lists\OIG;
use CDM\Test\TestCase;
use Mockery;

/**
 * Unit test for ExclusionListService 
 * 
 * To run, execute the following in the shell : vendor/bin/codecept run unit ExclusionListServiceTest --debug
 *
 */
class ExclusionListServiceTest extends TestCase
{

    private $service;
    
    private $exclusionListVersionRepo;
    private $exclusionListRepo;
    private $exclusionListFileRepo;
    
    private $listProcessorMock;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->app->withFacades();
        
        $this->exclusionListVersionRepo = Mockery::mock('App\Repositories\ExclusionListVersionRepository')->makePartial();
        $this->exclusionListRepo = Mockery::mock('App\Repositories\ExclusionListRepository')->makePartial();
        $this->exclusionListFileRepo = Mockery::mock('App\Repositories\ExclusionListFileRepository');
        
        $this->service = Mockery::mock('App\Services\ExclusionListService', [
            $this->exclusionListVersionRepo,
            $this->exclusionListRepo,
            $this->exclusionListFileRepo
        ])->makePartial();
        
        $this->service->shouldAllowMockingProtectedMethods();
        
        $this->withoutEvents();
    }

    public function tearDown()
    {
       	Mockery::close();
    }
    
    public function testGetExclusionListShouldReturnActiveExclusionListsWithUpdateRequiredTrueIfThereAreNoFilesForList()
    {   
        $this->exclusionListRepo->shouldReceive('getActiveExclusionLists')->once()->andReturn([
            (object)[
                'id' => 1,
                'prefix' => 'oig',
                'accr' => 'Federal OIG',
                'description' => 'Office of the Inspector General',
                'import_url' => 'http://www.fedoig.com',
                'is_auto_import' => 0,
                'is_active' => 1
            ]
        ]);
        
        // No file in files repo for list, update_required should be true
        $this->exclusionListFileRepo->shouldReceive('getFilesForPrefix')->once()->with('oig')->andReturnNull();
        
        $actual = $this->service->getActiveExclusionLists();
        
        $expected = [
            'oig' => [
                'id' => 1,
                'prefix' => 'oig',
                'accr' => 'Federal OIG',
                'description' => 'Office of the Inspector General',
                'import_url' => 'http://www.fedoig.com',
                'is_auto_import' => 0,
                'is_active' => 1,
                'update_required' => true
            ]
        ];
        
        $this->assertEquals($expected, $actual);
        
    }
    
    public function testGetExclusionListShouldReturnActiveExclusionListsWithUpdateRequiredFalseIfListIsUpToDate()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
        
        $this->exclusionListRepo->shouldReceive('getActiveExclusionLists')->once()->andReturn([
            (object)[
                'id' => 1,
                'prefix' => 'tn1',
                'accr' => 'TennCare',
                'description' => 'Tennesee State Medicaid Program',
                'import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf',
                'is_auto_import' => '0',
                'is_active' => '1'
            ]
        ]);
    
        $hash = hash_file('sha256', $exclusionListTestFile);

        // 
        $this->exclusionListFileRepo->shouldReceive('getFilesForPrefix')->once()->with('tn1')->andReturn([(object)[
            'state_prefix' => 'tn1',
            'img_data' => file_get_contents($exclusionListTestFile),
            'hash' => $hash,
            'img_type' => 'pdf'
        ]]);
    
        $this->exclusionListVersionRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
            'prefix' => 'tn1',
            'last_imported_hash' => $hash,
            'last_imported_date' => '2016-06-03 10:29:18'
        ]]);
        
        $actual = $this->service->getActiveExclusionLists();
    
        $expected = [
            'tn1' => [
                'id' => 1,
                'prefix' => 'tn1',
                'accr' => 'TennCare',
                'description' => 'Tennesee State Medicaid Program',
                'import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf',
                'is_auto_import' => '0',
                'is_active' => '1',
                'update_required' => false
            ]
        ];
    
        $this->assertEquals($expected, $actual);
    }
    
    public function testGetExclusionListShouldReturnActiveExclusionListsWithUpdateRequiredTrueIfListIsNotUpToDate()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0-dummy.pdf');
    
        $this->exclusionListRepo->shouldReceive('getActiveExclusionLists')->once()->andReturn([
            (object)[
                'id' => 1,
                'prefix' => 'tn1',
                'accr' => 'TennCare',
                'description' => 'Tennesee State Medicaid Program',
                'import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf',
                'is_auto_import' => '0',
                'is_active' => '1'
            ]
        ]);
    
        $hash = hash_file('sha256', $exclusionListTestFile);
    
        $this->exclusionListFileRepo->shouldReceive('getFilesForPrefix')->once()->with('tn1')->andReturn([(object)[
            'state_prefix' => 'tn1',
            'img_data' => file_get_contents($exclusionListTestFile),
            'hash' => 'some_old_hash',
            'img_type' => 'pdf'
        ]]);
    
        $this->exclusionListVersionRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
            'prefix' => 'tn1',
            'last_imported_hash' => $hash,
            'last_imported_date' => '2016-06-03 10:29:18'
        ]]);
    
        $actual = $this->service->getActiveExclusionLists();
    
        $expected = [
            'tn1' => [
                'id' => 1,
                'prefix' => 'tn1',
                'accr' => 'TennCare',
                'description' => 'Tennesee State Medicaid Program',
                'import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf',
                'is_auto_import' => '0',
                'is_active' => '1',
                'update_required' => true
            ]
        ];
    
        $this->assertEquals($expected, $actual);
    
    }    
}
