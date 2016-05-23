<?php

namespace Test\Unit;

use App\Services\ImportFileService;
use Mockery;
use CDM\Test\TestCase;
use App\Import\Lists\ExclusionList;
use App\Import\Lists\Tennessee;
use App\Import\Lists\OIG;

/**
 * Unit test for ImportFileService. 
 * 
 * To run, execute the following in the shell : vendor/bin/codecept run unit ImportFileServiceTest --debug
 *
 */
class ImportFileServiceTest extends TestCase
{

    private $importFileService;
    private $exclusionListDownloader;
    private $exclusionListRepo; 
    private $exclusionListFilesRepo;
    
    
//     protected $container;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->app->withFacades();
        
        $this->exclusionListFilesRepo = Mockery::mock('App\Repositories\ExclusionListFilesRepository')->shouldIgnoreMissing();
        $this->exclusionListRepo = Mockery::mock('App\Repositories\ExclusionListRepository')->shouldIgnoreMissing();
        $this->exclusionListDownloader = Mockery::mock('App\Services\ExclusionListHttpDownloader')->shouldIgnoreMissing();
        
        $this->importFileService = new ImportFileService($this->exclusionListDownloader, $this->exclusionListRepo, $this->exclusionListFilesRepo);
    }

    public function tearDown()
    {
       	Mockery::close();
    }
    
    public function testGetExclusionListShouldReturnActiveExclusionLists()
    {   
        $this->exclusionListRepo->shouldReceive('get')->once()->with(['is_active' => 1])->andReturn([
            (object)[
                'id' => 1,
                'prefix' => 'oig',
                'accr' => 'Federal OIG',
                'description' => 'Office of the Inspector General',
                'import_url' => 'http://www.fedoig.com',
                'is_auto_import' => 0,
                'is_active' => 1,
                'ready_for_update' => 'Y'
            ]
        ]);
        
        $actual = $this->importFileService->getExclusionList();
        
        $expected = [
            'oig' => [
                'id' => 1,
                'prefix' => 'oig',
                'accr' => 'Federal OIG',
                'description' => 'Office of the Inspector General',
                'import_url' => 'http://www.fedoig.com',
                'is_auto_import' => 0,
                'is_active' => 1,
                'ready_for_update' => 'Y'
            ]
        ];
        
        $this->assertEquals($expected, $actual);
        
    }

    public function testImportFileShouldRespondWithErrorIfUrlIsEmpty()
    {
        $actual = $this->importFileService->importFile('', 'nyomig');
        $expected = '{"success":false,"message":"No URL was specified for : nyomig"}';
        $this->assertEquals($expected, $actual->getContent());
    }
    
    public function testImportFileShouldCreateNewFileInRepositoryAndImportDataIfNoFileExistsYetForAnExclusionList()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
        
        //1. Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => $exclusionListTestFile]);  
        
        //2. File should be downloaded
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
        
        //3. Service should search in files repo for existing files
        $this->exclusionListFilesRepo->shouldReceive('exists')->with('tn1', 0)->andReturn(false);
        $this->exclusionListFilesRepo->shouldReceive('find')->once()->with('tn1', 0)->andReturn(null);
        
        //4. Service should add the files to the file repository
        $this->exclusionListFilesRepo->shouldNotReceive('update');
        $this->exclusionListFilesRepo->shouldReceive('create')->once()->with([
            'state_prefix' => 'tn1',
            'img_data_index' => 0,
            'img_data' => file_get_contents($exclusionListTestFile)
        ]);
        
        //5. Service should update ready_for_update flag to 'Y'
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['ready_for_update' => 'Y']);        
        
        //6. Service should check if ready for update is 'Y'
        $this->exclusionListRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
            'id' => '26', 
            'prefix' => 'tn1',
            'accr' => 'TennCare',
            'description' => 'Tennesee State Medicaid Program', 
            'import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf',
            'is_auto_import' => '0',
            'is_active' => '1',
            'ready_for_update' => 'Y'
        ]]);
        
        //7. Service should check if state_records is empty
        $this->exclusionListRepo->shouldReceive('isExclusionListRecordsEmpty')->with('tn1')->andReturn(true);
        
        //8. Service should insert the individual records
        $listProcessorMock = Mockery::mock('overload:App\Import\Service\ListProcessor'); 
        $listProcessorMock->shouldReceive('insertRecords')->once();
        
        //9. Service should update ready_for_update flag to 'N'
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['ready_for_update' => 'N']);
        
        $actual = $this->importFileService->importFile($exclusionListTestFile, 'tn1');
        
        $expected = '{"success":true,"message":""}';
        
        //10. Service should return a successful response
        $this->assertEquals($expected, $actual->getContent());
        
    }
    
    public function testImportFileShouldUpdateExistingFileInRepositoryAndImportDataIfFileInRepositoryIsStale()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');

        //1. Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => $exclusionListTestFile]);
    
        //2. File should be downloaded
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        //3. Service should search in files repo for existing files
        $this->exclusionListFilesRepo->shouldReceive('exists')->with('tn1', 0)->andReturn(true);
        $this->exclusionListFilesRepo->shouldReceive('find')->once()->with('tn1', 0)->andReturn([(object)[
            'id' => '14',
            'state_prefix' => 'tn1',
            'img_data_index' => '0',
            'img_data' => file_get_contents(base_path('tests/unit/files/tn1-0-dummy.pdf')),
            'date_created' => '2016-05-23 09:15:40',
            'date_modified' => '2016-05-23 09:15:40'
        ]]);
    
        //4. Service should update  the file in the file repository and not create a new record
        $this->exclusionListFilesRepo->shouldNotReceive('create');
        $this->exclusionListFilesRepo->shouldReceive('update')->once()->with('tn1', 0, [
            'img_data' => file_get_contents($exclusionListTestFile)
        ]);
        
    
        //5. Service should update ready_for_update flag to 'Y'
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['ready_for_update' => 'Y']);
    
        //6. Service should check if ready for update is 'Y'
        $this->exclusionListRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
            'id' => '26',
            'prefix' => 'tn1',
            'accr' => 'TennCare',
            'description' => 'Tennesee State Medicaid Program',
            'import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf',
            'is_auto_import' => '0',
            'is_active' => '1',
            'ready_for_update' => 'Y'
        ]]);
    
        //7. Service should check if state_records is empty
        $this->exclusionListRepo->shouldReceive('isExclusionListRecordsEmpty')->with('tn1')->andReturn(true);
    
        //8. Service should insert the individual records
        $listProcessorMock = Mockery::mock('overload:App\Import\Service\ListProcessor');
        $listProcessorMock->shouldReceive('insertRecords')->once();
    
        //9. Service should update ready_for_update flag to 'N'
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['ready_for_update' => 'N']);
    
        $actual = $this->importFileService->importFile($exclusionListTestFile, 'tn1');
    
        $expected = '{"success":true,"message":""}';
    
        //10. Service should return a successful response
        $this->assertEquals($expected, $actual->getContent());
    
    }
    
    public function testImportFileShouldNotImportDataIfFileInRepositoryIsAlreadyUpToDate()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        //1. Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => $exclusionListTestFile]);
    
        //2. File should be downloaded
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        //3. Service should search in files repo for existing files
        $this->exclusionListFilesRepo->shouldReceive('exists')->with('tn1', 0)->andReturn(true);
        $this->exclusionListFilesRepo->shouldReceive('find')->once()->with('tn1', 0)->andReturn([(object)[
            'id' => '14',
            'state_prefix' => 'tn1',
            'img_data_index' => '0',
            'img_data' => file_get_contents($exclusionListTestFile), //same as the downloaded file, so no import should take place
            'date_created' => '2016-05-23 09:15:40',
            'date_modified' => '2016-05-23 09:15:40'
        ]]);
    
        //4. Service should not update or create any files in the file repository since it is already up to date
        $this->exclusionListFilesRepo->shouldNotReceive('create');
        $this->exclusionListFilesRepo->shouldNotReceive('update');
    
        //5. Service should check if ready for update is 'Y'
        $this->exclusionListRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
            'id' => '26',
            'prefix' => 'tn1',
            'accr' => 'TennCare',
            'description' => 'Tennesee State Medicaid Program',
            'import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf',
            'is_auto_import' => '0',
            'is_active' => '1',
            'ready_for_update' => 'N' //ready_for_update is 'N' when state is up to date
        ]]);
    
        //6. Service should check if state_records is empty
        $this->exclusionListRepo->shouldReceive('isExclusionListRecordsEmpty')->with('tn1')->andReturn(false);
    
        //7. Service should not do importing
        $listProcessorMock = Mockery::mock('overload:App\Import\Service\ListProcessor');
        $listProcessorMock->shouldNotReceive('insertRecords');
    
        //8. Service should not receive any update update ready_for_update flag to 'N'
        $this->exclusionListRepo->shouldNotReceive('update');
    
        $actual = $this->importFileService->importFile($exclusionListTestFile, 'tn1');
    
        $expected = '{"success":true,"message":"State is already up-to-date."}';
    
        //9. Service should return a successful response
        $this->assertEquals($expected, $actual->getContent());
    
    }
    
    public function testRefreshRecordsShouldOnlyImportExclusionListsFlaggedForAutoImport()
    {
        $importFileService = Mockery::mock('App\Services\ImportFileService[importFile]', [$this->exclusionListDownloader, $this->exclusionListRepo, $this->exclusionListFilesRepo]);
        
        //1. All exclusion lists should be queried
        $this->exclusionListRepo->shouldReceive('get')->withNoArgs()->andReturn([
            (object)[
                'id' => 1,
                'prefix' => 'oig',
                'accr' => 'Federal OIG',
                'description' => 'Office of the Inspector General',
                'import_url' => 'http://www.fedoig.com',
                'is_auto_import' => 0, //Not auto-import, so this should not be imported 
                'is_active' => 1,
                'ready_for_update' => 'Y'
            ],
            (object)[
                'id' => 2,
                'prefix' => 'nyomig',
                'accr' => 'NY OMIG',
                'description' => 'New York Office of the Medical Inspector General',
                'import_url' => 'http://www.omig.ny.gov/data/gensplistns.php',
                'is_auto_import' => 1, //Auto-import
                'is_active' => 1,
                'ready_for_update' => 'N'            
            ]
        ]);
        
        //2. The url should be searched from the exclusion_lists table
        $this->exclusionListRepo->shouldReceive('find')->once()->with('oig')->andReturn([(object)[
                'id' => 1,
                'prefix' => 'oig',
                'accr' => 'Federal OIG',
                'description' => 'Office of the Inspector General',
                'import_url' => 'http://www.fedoig.com',
                'is_auto_import' => 0, //Not auto-import, so this should not be imported 
                'is_active' => 1,
                'ready_for_update' => 'Y'
            ]
        ]);        
        $this->exclusionListRepo->shouldReceive('find')->once()->with('nyomig')->andReturn([(object)[
                'id' => 2,
                'prefix' => 'nyomig',
                'accr' => 'NY OMIG',
                'description' => 'New York Office of the Medical Inspector General',
                'import_url' => 'http://www.omig.ny.gov/data/gensplistns.php',
                'is_auto_import' => 1, //Auto-import
                'is_active' => 1,
                'ready_for_update' => 'Y'            
            ]
        ]);
         
        //3. The importFile method of ImportFileService should be called
        $importFileService->shouldReceive('importFile')->once()->with('http://www.omig.ny.gov/data/gensplistns.php', 'nyomig');
        
        $importFileService->refreshRecords();
    } 
    
    public function testRefreshRecordsShouldNotImportedExclusionListsNotFlaggedForAutoImportButUpdateTheirRepositoryFiles()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        $importFileService = Mockery::mock('App\Services\ImportFileService[importFile]', [$this->exclusionListDownloader, $this->exclusionListRepo, $this->exclusionListFilesRepo]);
    
        //1. All exclusion lists should be queried
        $this->exclusionListRepo->shouldReceive('get')->withNoArgs()->andReturn([
            (object)[
                'id' => 1,
                'prefix' => 'tn1',
                'accr' => 'TennCare',
                'description' => 'Tennesee State Medicaid Program',
                'import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf',
                'is_auto_import' => '0',
                'is_active' => '1',
                'ready_for_update' => 'N' //ready_for_update is 'N' when state is up to date
            ],
            (object)[
                'id' => 2,
                'prefix' => 'nyomig',
                'accr' => 'NY OMIG',
                'description' => 'New York Office of the Medical Inspector General',
                'import_url' => 'http://www.omig.ny.gov/data/gensplistns.php',
                'is_auto_import' => 1, //Auto-import
                'is_active' => 1,
                'ready_for_update' => 'N'
            ]
        ]);
        
        //2. The urls should be searched from the exclusion_lists table
        $this->exclusionListRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
                'id' => 1,
                'prefix' => 'tn1',
                'accr' => 'TennCare',
                'description' => 'Tennesee State Medicaid Program',
                'import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf',
                'is_auto_import' => '0',
                'is_active' => '1',
                'ready_for_update' => 'N' //ready_for_update is 'N' when state is up to date
            ]
        ]);
        
        $this->exclusionListRepo->shouldReceive('find')->once()->with('nyomig')->andReturn([(object)[
                'id' => 2,
                'prefix' => 'nyomig',
                'accr' => 'NY OMIG',
                'description' => 'New York Office of the Medical Inspector General',
                'import_url' => 'http://www.omig.ny.gov/data/gensplistns.php',
                'is_auto_import' => 1, //Auto-import
                'is_active' => 1,
                'ready_for_update' => 'N'            
            ]
        ]);
        
        //3. The urls should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
        $this->exclusionListRepo->shouldReceive('update')->once()->with('nyomig', ['import_url' => 'http://www.omig.ny.gov/data/gensplistns.php']);
        
        //4. The importFile method of ImportFileService should be called only for nyomig
        $importFileService->shouldReceive('importFile')->once()->with('http://www.omig.ny.gov/data/gensplistns.php', 'nyomig');
        
        //5. For tn1, which was not marked for auto-import, its file in the repository should get updated
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->withArgs(function($args){
            return $args instanceof Tennessee;    
        })->andReturn([$exclusionListTestFile]);
        
        //6. Service should search in files repo for existing files
        $this->exclusionListFilesRepo->shouldReceive('exists')->with('tn1', 0)->andReturn(true);
        $this->exclusionListFilesRepo->shouldReceive('find')->once()->with('tn1', 0)->andReturn([(object)[
            'id' => '14',
            'state_prefix' => 'tn1',
            'img_data_index' => '0',
            'img_data' => file_get_contents(base_path('tests/unit/files/tn1-0-dummy.pdf')),
            'date_created' => '2016-05-23 09:15:40',
            'date_modified' => '2016-05-23 09:15:40'
        ]]);
        
        //7. Service should update  the file in the file repository and not create a new record
        $this->exclusionListFilesRepo->shouldNotReceive('create');
        $this->exclusionListFilesRepo->shouldReceive('update')->once()->with('tn1', 0, [
            'img_data' => file_get_contents($exclusionListTestFile)
        ]);
        
        
        //8. Service should update ready_for_update flag to 'Y'
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['ready_for_update' => 'Y']);
        
        $importFileService->refreshRecords();
    }    
}
