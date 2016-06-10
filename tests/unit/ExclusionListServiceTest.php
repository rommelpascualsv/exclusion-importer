<?php

namespace Test\Unit;

use App\Import\Lists\OIG;
use CDM\Test\TestCase;
use Mockery;
use App\Services\ExclusionListService;

/**
 * Unit test for ExclusionListService 
 * 
 * To run, execute the following in the shell : vendor/bin/codecept run unit ExclusionListServiceTest --debug
 *
 */
class ExclusionListServiceTest extends TestCase
{

    private $service;
    private $exclusionListRepo;
    private $exclusionListFileRepo;
    private $exclusionListStatusHelper;
        
    public function setUp()
    {
        parent::setUp();
        
        $this->app->withFacades();
        
        $this->exclusionListRepo = Mockery::mock('App\Repositories\ExclusionListRepository')->makePartial();
        $this->exclusionListFileRepo = Mockery::mock('App\Repositories\ExclusionListFileRepository')->makePartial();
        $this->exclusionListStatusHelper = Mockery::mock('App\Services\ExclusionListStatusHelper')->makePartial();
        
        $this->service = new ExclusionListService(
            $this->exclusionListRepo,
            $this->exclusionListFileRepo,
            $this->exclusionListStatusHelper
        );
    }

    public function tearDown()
    {
       	Mockery::close();
    }
    
    public function testGetExclusionListShouldReturnActiveExclusionLists()
    {   
        $this->exclusionListRepo->shouldReceive('getActiveExclusionLists')->once()->andReturn([
            (object)[
                'id' => 1,
                'prefix' => 'oig',
                'accr' => 'Federal OIG',
                'description' => 'Office of the Inspector General',
                'import_url' => 'http://www.fedoig.com',
                'is_auto_import' => 0,
                'is_active' => 1,
                'last_imported_hash' => '0e0e8f90a0425a08413cec6248b1d60feea475ca01d7dab33d0c6cfdde5e81bf',
                'last_imported_date' => '2016-06-09 11:43:54',
                'last_import_stats' => '{"added":6025,"deleted":0,"brokenHashPct":0,"previousRecordCount":0,"currentRecordCount":6044}'
            ]
        ]);
        
        $this->exclusionListFileRepo->shouldReceive('getFilesForPrefix')->once()->with('oig')->andReturn([(object)[
            'state_prefix' => 'oig',
            'img_data' => null,
            'hash' => 'latest_file_hash',
            'img_type' => 'zip'
        ]]);
        
        $this->exclusionListStatusHelper->shouldReceive('isUpdateRequired')->once()->with('oig', 'latest_file_hash')->andReturnTrue();
        
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
                'update_required' => true,
                'last_imported_hash' => '0e0e8f90a0425a08413cec6248b1d60feea475ca01d7dab33d0c6cfdde5e81bf',
                'last_imported_date' => '2016-06-09 11:43:54',
                'last_import_stats' => '{"added":6025,"deleted":0,"brokenHashPct":0,"previousRecordCount":0,"currentRecordCount":6044}'
            ]
        ];
        
        $this->assertEquals($expected, $actual);
        
    }
}
