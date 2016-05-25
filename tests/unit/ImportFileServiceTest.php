<?php

namespace Test\Unit;

use App\Import\Lists\OIG;
use App\Import\Lists\Tennessee;
use App\Services\ImportFileService;
use CDM\Test\TestCase;
use Mockery;

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
    private $exclusionListFileRepo;
    private $exclusionListRecordRepo;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->app->withFacades();
        
        $this->exclusionListFileRepo = Mockery::mock('App\Repositories\ExclusionListFileRepository')->makePartial();
        $this->exclusionListRepo = Mockery::mock('App\Repositories\ExclusionListRepository')->makePartial();
        $this->exclusionListRecordRepo = Mockery::mock('App\Repositories\ExclusionListRecordRepository')->makePartial();
        $this->exclusionListDownloader = Mockery::mock('App\Services\ExclusionListHttpDownloader')->makePartial();
        
        $this->importFileService = Mockery::mock('App\Services\ImportFileService', [$this->exclusionListDownloader, $this->exclusionListRepo, $this->exclusionListFileRepo, $this->exclusionListRecordRepo])->makePartial();
        $this->importFileService->shouldAllowMockingProtectedMethods();
    }

    public function tearDown()
    {
       	Mockery::close();
    }
    
    public function testGetExclusionListShouldReturnActiveExclusionLists()
    {   
        $this->exclusionListRepo->shouldReceive('query')->once()->with(['is_active' => 1])->andReturn([
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
    
    public function testImportFileShouldCreateNewFileVersionInRepositoryAndImportDataIfNoFileExistsInRepository()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
        
        //1. Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);  
        
        //2. File should be downloaded
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);

        //3. Service should search in files repo for existing files
        $this->exclusionListFileRepo->shouldReceive('find')->once()->with(['tn1', 0])->andReturn(null);
        
        $this->importFileService->shouldReceive('createFileVersion')->andReturn('VERSION_1');
        
        //4. Service should add the files to the file repository with a version
        $this->exclusionListFileRepo->shouldReceive('create')->once()->with([
            'state_prefix' => 'tn1',
            'img_data_index' => 0,
            'img_data_version' => 'VERSION_1',
            'img_data' => file_get_contents($exclusionListTestFile)
        ]);
        
        //5. Service should update ready_for_update flag to 'Y'
        $this->exclusionListRepo->shouldNotReceive('create');
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
        $this->exclusionListRecordRepo->shouldReceive('size')->with('tn1')->andReturn(0);
        
        //8. Service should insert the individual records
        $listProcessorMock = Mockery::mock('overload:App\Import\Service\ListProcessor'); 
        $listProcessorMock->shouldReceive('insertRecords')->once();
        
        //9. Service should update ready_for_update flag to 'N'
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['ready_for_update' => 'N']);
        
        $actual = $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');
        
        $expected = '{"success":true,"message":""}';
        
        //10. Service should return a successful response
        $this->assertEquals($expected, $actual->getContent());
        
    }
    
    public function testImportFileShouldCreateNewFileVersionAndImportDataIfLatestFileVersionInRepositoryIsNotEqualToDownloadedFile()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');

        //1. Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
    
        //2. File should be downloaded
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        //3. Service should search in files repo for existing files
        $this->exclusionListFileRepo->shouldReceive('find')->once()->with(['tn1', 0])->andReturn([
            (object)[
                'id' => '14',
                'state_prefix' => 'tn1',
                'img_data_index' => '0',
                'img_data_version' => 'VERSION_1',
                'img_data' => file_get_contents($exclusionListTestFile), //same as downloaded file but an earlier version - should not be detected as the latest version to compare to the downloaded file
                'date_created' => '2016-05-23 09:15:40',
                'date_modified' => '2016-05-23 09:15:40'
            ],            
            (object)[
                'id' => '14',
                'state_prefix' => 'tn1',
                'img_data_index' => '0',
                'img_data_version' => 'VERSION_2',
                'img_data' => file_get_contents(base_path('tests/unit/files/tn1-0-dummy.pdf')), //Not the same as the downloaded file, should trigger creation of new version
                'date_created' => '2016-05-23 09:15:40',
                'date_modified' => '2016-05-23 09:15:40'
            ]
        ]);
    
        //4. Service should add the file in the file repository with a new version
        $this->importFileService->shouldReceive('createFileVersion')->andReturn('VERSION_3');
        
        //4. Service should add the files to the file repository with a version
        $this->exclusionListFileRepo->shouldReceive('create')->once()->with([
            'state_prefix' => 'tn1',
            'img_data_index' => 0,
            'img_data_version' => 'VERSION_3',
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
        $this->exclusionListRecordRepo->shouldReceive('size')->with('tn1')->andReturn(0);
    
        //8. Service should insert the individual records
        $listProcessorMock = Mockery::mock('overload:App\Import\Service\ListProcessor');
        $listProcessorMock->shouldReceive('insertRecords')->once();
    
        //9. Service should update ready_for_update flag to 'N'
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['ready_for_update' => 'N']);
    
        $actual = $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');
    
        $expected = '{"success":true,"message":""}';
    
        //10. Service should return a successful response
        $this->assertEquals($expected, $actual->getContent());
    
    }
    
    public function testImportFileShouldNotImportDataIfLatestFileVersionInRepositoryIsEqualToDownloadedFile()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        // 1. Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
    
        // 2. File should be downloaded
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        // 3. Service should search in files repo for existing files
        $this->exclusionListFileRepo->shouldReceive('find')->once()->with(['tn1', 0])->andReturn([(object)[
            'id' => '14',
            'state_prefix' => 'tn1',
            'img_data_index' => '0',
            'img_data_version' => 'VERSION_1',
            'img_data' => file_get_contents($exclusionListTestFile), //same as the downloaded file, so no import should take place
            'date_created' => '2016-05-23 09:15:40',
            'date_modified' => '2016-05-23 09:15:40'
        ]]);
    
        // 4. Service should not update or create any files in the file repository since it is already up to date
        $this->exclusionListFileRepo->shouldNotReceive('create');
        $this->exclusionListFileRepo->shouldNotReceive('update');
    
        // 5. Service should check if ready for update is 'Y'
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
    
        // 6. Service should check if state_records is empty
        $this->exclusionListRecordRepo->shouldReceive('size')->with('tn1')->andReturn(1);
    
        // 7. Service should not do importing
        $listProcessorMock = Mockery::mock('overload:App\Import\Service\ListProcessor');
        $listProcessorMock->shouldNotReceive('insertRecords');
    
        // 8. Service should not receive any update update ready_for_update flag to 'N'
        $this->exclusionListRepo->shouldNotReceive('update');
    
        $actual = $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');
    
        $expected = '{"success":true,"message":"State is already up-to-date."}';
    
        // 9. Service should return a successful response
        $this->assertEquals($expected, $actual->getContent());
    
    }
    
    public function testImportFileShouldImportDataForNonDownloadableExclusionListContentEvenIfAlreadyUpToDateAndRecordsExist()
    {
        // 1. Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('az1', ['import_url' => 'https://web.archive.org/web/20150609233844/http://www.azahcccs.gov/OIG/ExludedProviders.aspx']);
    
        // 2. File should not be downloaded since it is not downloadable
        $this->exclusionListDownloader->shouldNotReceive('downloadFiles');
    
        // 3. Since there is no downloaded file, there shouldn't be any searching done in the files repo
        $this->exclusionListFileRepo->shouldNotReceive('find');
    
        // 4. Service should not update or create any files in the file repository
        $this->exclusionListFileRepo->shouldNotReceive('create');
        $this->exclusionListFileRepo->shouldNotReceive('update');
        $this->importFileService->shouldNotReceive('createFileVersion');
    
        // 5. Service should not check if ready for update is 'Y'
        $this->exclusionListRepo->shouldNotReceive('find')->with('az1');
        
        // 6. Service should check if state_records is empty
        $this->exclusionListRecordRepo->shouldReceive('size')->with('az1')->andReturn(0);
    
        // 7. Service should not do importing
        $listProcessorMock = Mockery::mock('overload:App\Import\Service\ListProcessor');
        $listProcessorMock->shouldReceive('insertRecords');
    
        // 8. Service should not receive any update update ready_for_update flag to 'N'
        $this->exclusionListRepo->shouldReceive('update')->once()->with('az1', ['ready_for_update' => 'N']);
        
        //Importing Arizona, whose type is not downloadable because it is html, should always result in an import
        $actual = $this->importFileService->importFile('https://web.archive.org/web/20150609233844/http://www.azahcccs.gov/OIG/ExludedProviders.aspx', 'az1');
    
        $expected = '{"success":true,"message":""}';
    
        //9. Service should return a successful response
        $this->assertEquals($expected, $actual->getContent());
    
    }    
    
    public function testRefreshRecordsShouldOnlyImportExclusionListsFlaggedForAutoImport()
    {
        $importFileService = Mockery::mock('App\Services\ImportFileService[importFile]', [$this->exclusionListDownloader, $this->exclusionListRepo, $this->exclusionListFileRepo, $this->exclusionListRecordRepo])->makePartial();
        $importFileService->shouldAllowMockingProtectedMethods();
        
        // 1. All exclusion lists should be queried
        $this->exclusionListRepo->shouldReceive('query')->withNoArgs()->andReturn([
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
        
        // 2. The url should be searched from the exclusion_lists table
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
         
        // 3. The import_url should not be updated
        $this->exclusionListRepo->shouldNotReceive('update')->with('nyomig', ['import_url' => 'http://www.omig.ny.gov/data/gensplistns.php']);
        
        // 4. The importFile method of ImportFileService should be called
        $importFileService->shouldReceive('importFile')->once()->with('http://www.omig.ny.gov/data/gensplistns.php', 'nyomig');
        
        $importFileService->refreshRecords();
    } 
    
    public function testRefreshRecordsShouldJustUpdateRepositoryFilesOfNonAutoImportListsWithDownloadableContentIfLatestFileVersionInRepositoryIsNotEqualToDownloadedFile()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        $importFileService = Mockery::mock('App\Services\ImportFileService[importFile,createFileVersion]', [$this->exclusionListDownloader, $this->exclusionListRepo, $this->exclusionListFileRepo, $this->exclusionListRecordRepo])->makePartial();
        $importFileService->shouldAllowMockingProtectedMethods();
        
        // 1. All exclusion lists should be queried
        $this->exclusionListRepo->shouldReceive('query')->withNoArgs()->andReturn([
            (object)[
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
        
        // 2. The urls should be searched from the exclusion_lists table
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
        
        // 3. The import_url should not be updated
        $this->exclusionListRepo->shouldNotReceive('update')->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
        
        // 4. File in the repository should get updated
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->withArgs(function($args){
            return $args instanceof Tennessee;    
        })->andReturn([$exclusionListTestFile]);
        
        // 5. Service should search in files repo for existing files
        $this->exclusionListFileRepo->shouldReceive('find')->once()->with(['tn1', 0])->andReturn([(object)[
            'id' => '14',
            'state_prefix' => 'tn1',
            'img_data_index' => '0',
            'img_data_version' => 'VERSION_1',
            'img_data' => file_get_contents(base_path('tests/unit/files/tn1-0-dummy.pdf')), //Repository file should be detected as stale and a new file should get inserted 
            'date_created' => '2016-05-23 09:15:40',
            'date_modified' => '2016-05-23 09:15:40'
        ]]);
        
        // 6. Service should insert downloaded file in files repository with a new version
        $importFileService->shouldReceive('createFileVersion')->andReturn('VERSION_2');

        $this->exclusionListFileRepo->shouldReceive('create')->once()->with([
            'state_prefix' => 'tn1',
            'img_data_index' => 0,
            'img_data_version' => 'VERSION_2',
            'img_data' => file_get_contents($exclusionListTestFile)
        ]);            
        
        // 7. Service should update ready_for_update flag to 'Y'
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['ready_for_update' => 'Y']);
        
        // 8. We should just be updating the repository files for non auto-import lists but not import them
        $importFileService->shouldNotReceive('importFile');
        
        $importFileService->refreshRecords();
    }
    
    public function testRefreshRecordsShouldNotUpdateRepositoryFilesOfNonAutoImportListsWithDownloadableContentIfLatestFileVersionInRepositoryIsEqualToDownloadedFile()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        $importFileService = Mockery::mock('App\Services\ImportFileService[importFile,createFileVersion]', [$this->exclusionListDownloader, $this->exclusionListRepo, $this->exclusionListFileRepo, $this->exclusionListRecordRepo])->makePartial();
        $importFileService->shouldAllowMockingProtectedMethods();
    
        // 1. All exclusion lists should be queried
        $this->exclusionListRepo->shouldReceive('query')->withNoArgs()->andReturn([
            (object)[
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
    
        // 2. The urls should be searched from the exclusion_lists table
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
    
        // 3. The import_url should not be updated
        $this->exclusionListRepo->shouldNotReceive('update')->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
    
        // 4. File in the repository should get updated
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->withArgs(function($args){
            return $args instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        // 5. Service should search in files repo for existing files
        $this->exclusionListFileRepo->shouldReceive('find')->once()->with(['tn1', 0])->andReturn([(object)[
            'id' => '14',
            'state_prefix' => 'tn1',
            'img_data_index' => '0',
            'img_data_version' => 'VERSION_1',
            'img_data' => file_get_contents($exclusionListTestFile), //same as the downloaded file, so no import should take place
            'date_created' => '2016-05-23 09:15:40',
            'date_modified' => '2016-05-23 09:15:40'
        ]]);
    
        // 6. Service should not create any new files in the repository
        $importFileService->shouldNotReceive('createFileVersion');
        $this->exclusionListFileRepo->shouldNotReceive('create');
    
        // 7. Service should not update ready_for_update flag to 'Y'
        $this->exclusionListRepo->shouldNotReceive('update')->with('tn1', ['ready_for_update' => 'Y']);
    
        // 8. We should just be updating the repository files for non auto-import lists but not import them
        $importFileService->shouldNotReceive('importFile');
        
        $importFileService->refreshRecords();
    }    
    
    public function testRefreshRecordsShouldJustUpdateReadyForUpdateFlagToYForNonAutoImportListsWithNonDownloadableContent()
    {
        $importFileService = Mockery::mock('App\Services\ImportFileService', [$this->exclusionListDownloader, $this->exclusionListRepo, $this->exclusionListFileRepo, $this->exclusionListRecordRepo])->makePartial();
        $importFileService->shouldAllowMockingProtectedMethods();
        
        // 1. All exclusion lists should be queried
        $this->exclusionListRepo->shouldReceive('query')->withNoArgs()->andReturn([
            (object)[
                'id' => 25,
                'prefix' => 'az1',
                'accr' => 'AZ AHCCCS',
                'description' => 'Arizona Office of the Inspector General',
                'import_url' => 'https://web.archive.org/web/20150609233844/http://www.azahcccs.gov/OIG/ExludedProviders.aspx',
                'is_auto_import' => '0',
                'is_active' => '1',
                'ready_for_update' => 'N' //ready_for_update is 'N' when state is up to date
            ]
        ]);
        
        // 2. The urls should be searched from the exclusion_lists table
        $this->exclusionListRepo->shouldReceive('find')->once()->with('az1')->andReturn([
            (object)[
                'id' => 25,
                'prefix' => 'az1',
                'accr' => 'AZ AHCCCS',
                'description' => 'Arizona Office of the Inspector General',
                'import_url' => 'https://web.archive.org/web/20150609233844/http://www.azahcccs.gov/OIG/ExludedProviders.aspx',
                'is_auto_import' => '0',
                'is_active' => '1',
                'ready_for_update' => 'N' //ready_for_update is 'N' when state is up to date
            ]
        ]);
    
        // 3. The import_url should not be updated
        $this->exclusionListRepo->shouldNotReceive('update')->with('az1', ['import_url' => 'https://web.archive.org/web/20150609233844/http://www.azahcccs.gov/OIG/ExludedProviders.aspx']);
    
        // 4. File should not be downloaded (since it is not downloadable)
        $this->exclusionListDownloader->shouldNotReceive('downloadFiles');
        
        // 5. Service should search in files repo for existing files
        $this->exclusionListFileRepo->shouldNotReceive('find');
    
        // 6. Service should update  the file in the file repository and not create a new record
        $this->exclusionListFileRepo->shouldNotReceive('create');
        $this->exclusionListFileRepo->shouldNotReceive('update');
        $importFileService->shouldNotReceive('createFileVersion');
    
        // 7. Service should update ready_for_update flag to 'Y'
        $this->exclusionListRepo->shouldReceive('update')->once()->with('az1', ['ready_for_update' => 'Y']);
    
        // 8. We should just be updating the repository files for non auto-import lists but not import them
        $importFileService->shouldNotReceive('importFile');
        $importFileService->refreshRecords();
    }    
}
