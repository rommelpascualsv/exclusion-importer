<?php

namespace Test\Unit;

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
    private $exclusionListStatusHelper;
    
    private $listProcessorMock;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->app->withFacades();
        
        $this->exclusionListDownloader = Mockery::mock('App\Services\ExclusionListHttpDownloader')->makePartial();
        $this->exclusionListFileRepo = Mockery::mock('App\Repositories\FileRepository')->makePartial();
        $this->exclusionListRepo = Mockery::mock('App\Repositories\ExclusionListRepository')->makePartial();
        $this->exclusionListRecordRepo = Mockery::mock('App\Repositories\ExclusionListRecordRepository')->makePartial();
        $this->exclusionListStatusHelper = Mockery::mock('App\Services\ExclusionListStatusHelper')->makePartial();
        
        $this->listProcessorMock = Mockery::mock('App\Import\Service\ListProcessor');
        
        $this->importFileService = Mockery::mock('App\Services\ImportFileService', [
            $this->exclusionListDownloader, 
            $this->exclusionListRepo, 
            $this->exclusionListFileRepo, 
            $this->exclusionListRecordRepo,
            $this->exclusionListStatusHelper
        ])->makePartial();
        
        $this->importFileService->shouldAllowMockingProtectedMethods();
        
        $this->withoutEvents();
    }

    public function tearDown()
    {
       	Mockery::close();
    }
    
    public function testImportFileShouldRespondWithErrorIfUrlIsEmpty()
    {
        $actual = $this->importFileService->importFile('', 'nyomig');
        $expected = '{"success":false,"message":"No URL was specified for : nyomig","data":null}';
        $this->assertEquals($expected, $actual->getContent());
    }
    
    public function testImportFileShouldRespondWithErrorMessageIfFileDownloadFails()
    {
        // Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
    
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andThrow(new \Exception('Error downloading file'));
    
        // Service should not add any files to the file repository with a version
        $this->exclusionListFileRepo->shouldNotReceive('create');
        $this->exclusionListFileRepo->shouldNotReceive('update');
    
        // Service should not insert any records
        $this->importFileService->shouldReceive('createListProcessor')->andReturn($this->listProcessorMock);
        $this->listProcessorMock->shouldNotReceive('insertRecords');
    
        $actual = $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');

        $expected = '{"success":false,"message":"Error importing exclusion list for \'tn1\' : Error downloading file","data":null}';
        
        //Service should return an error message
        $this->assertEquals($expected, $actual->getContent());
    }
    
    public function testImportFileShouldAddHashToTheFilesRepositoryIfHashDoesNotExistInTheFilesRepository()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        // Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
        
        // Service should download files
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        // Service should check if hash already exists for file in files repository
        $hash = hash_file('sha256', $exclusionListTestFile);
        
        $this->exclusionListStatusHelper->shouldReceive('isUpdateRequired')->once()->with('tn1', $hash)->andReturnTrue();
        
        $this->exclusionListFileRepo->shouldReceive('contains')->once()->with([
            'state_prefix' => 'tn1',
            'hash' => $hash,
            'img_type' => 'pdf'
        ])->andReturn(false);
        
        // Service should add hash to files repository
        $this->exclusionListFileRepo->shouldReceive('create')->once()->withArgs(function($args) use ($hash){
            return $args['state_prefix'] === 'tn1' &&
                   $args['hash'] === $hash &&
                   $args['date_last_downloaded'] !== null;
        });
    
        $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');
    
    } 
    
    public function testImportFileShouldNotAddHashToTheFilesRepositoryIfHashAlreadyExistsInTheFilesRepository()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        // Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
    
        // Service should download files
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        $hash = hash_file('sha256', $exclusionListTestFile);

        $this->exclusionListStatusHelper->shouldReceive('isUpdateRequired')->once()->with('tn1', $hash)->andReturnTrue();
        
        // Service should check if hash already exists for file in files repository
        $this->exclusionListFileRepo->shouldReceive('contains')->once()->with([
            'state_prefix' => 'tn1',
            'hash' => $hash,
            'img_type' => 'pdf'
        ])->andReturn(true);
    
        // Service should not add hash to files repository since it already exists
        $this->exclusionListFileRepo->shouldNotReceive('create');
    
        $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');
    
    } 
    
    public function testImportFileShouldSaveFileContentsToRepositoryIfFileContentsInRepositoryIsNotEqualToDownloadedFile()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        // Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
    
        // Service should download files
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        $hash = hash_file('sha256', $exclusionListTestFile);
        
        // Service should check if hash already exists for file in files repository
        $this->exclusionListFileRepo->shouldReceive('contains')->once()->with([
            'state_prefix' => 'tn1',
            'hash' => $hash,
            'img_type' => 'pdf'
        ])->andReturn(true);
    
        // Service should not add hash to files repository since it already exists
        $this->exclusionListFileRepo->shouldNotReceive('create');
        
        // Service should fetch the files record to update with the file contents
        $this->exclusionListFileRepo->shouldReceive('getFilesForPrefixAndHash')->once()->with('tn1', $hash)->andReturn([(object)[
            'state_prefix' => 'tn1',
            'img_data' => file_get_contents('tests/unit/files/tn1-0-dummy.pdf'), //Not the same as the downloaded - should be updated
            'hash' => $hash,
            'img_type' => 'pdf'
        ]]);
        
        $this->exclusionListStatusHelper->shouldReceive('isUpdateRequired')->once()->with('tn1', $hash)->andReturnTrue();
        
        $this->exclusionListFileRepo->shouldReceive('update')->once()->with([
            'state_prefix' => 'tn1',
            'hash' => $hash
        ],[
            'img_data' => file_get_contents($exclusionListTestFile)
        ]);
        
        $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');
    
    } 
    
    public function testImportFileShouldNotSaveFileContentsToRepositoryIfFileContentsInRepositoryIsEqualToDownloadedFile()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        // Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
    
        // Service should download files
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        $hash = hash_file('sha256', $exclusionListTestFile);
        
        $this->exclusionListStatusHelper->shouldReceive('isUpdateRequired')->once()->with('tn1', $hash)->andReturnTrue();
        
        // Service should check if hash already exists for file in files repository
        $this->exclusionListFileRepo->shouldReceive('contains')->once()->with([
            'state_prefix' => 'tn1',
            'hash' => $hash,
            'img_type' => 'pdf'
        ])->andReturn(true);
    
        // Service should not add hash to files repository since it already exists
        $this->exclusionListFileRepo->shouldNotReceive('create');
        
        // Service should update last_downloaded_date of file
        $this->exclusionListFileRepo->shouldReceive('update')->once()->withArgs(function($record, $data) use ($hash) {
            return $record == ['state_prefix' => 'tn1', 'hash' => $hash, 'img_type' => 'pdf'] && $data['date_last_downloaded'];    
        });
    
        // Service should fetch the files record to update with the file contents
        $this->exclusionListFileRepo->shouldReceive('getFilesForPrefixAndHash')->once()->with('tn1', $hash)->andReturn([(object)[
            'state_prefix' => 'tn1',
            'img_data' => file_get_contents($exclusionListTestFile), //Same as downloaded - should not be updated
            'hash' => $hash,
            'img_type' => 'pdf'
        ]]);
    
        $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');
    
    }    
    
    public function testImportFileShouldZipMultipleExclusionListFilesAndSaveTheZipFileContentsIfRepositoryContentIsNotEqualToZipFileContents()
    {
        $exclusionListTestFileZip = null;
        
        try {

            $exclusionListTestFile1 = base_path('tests/unit/files/tn1-0.pdf');
            $exclusionListTestFile2 = base_path('tests/unit/files/tn1-0-dummy.pdf');
            $exclusionListTestFileZip = tempnam('tests/unit/files', 'tn1');
            
            create_zip([$exclusionListTestFile1, $exclusionListTestFile2], $exclusionListTestFileZip, true);
            
            // Url should be updated
            $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
            
            // Service should download files
            $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
                return $exclusionList instanceof Tennessee;
            })->andReturn([$exclusionListTestFile1, $exclusionListTestFile2]);
            
            
            $hash = hash_file('sha256', $exclusionListTestFileZip);
            
            $this->exclusionListStatusHelper->shouldReceive('isUpdateRequired')->once()->with('tn1', $hash)->andReturnTrue();
            
            // For zip files, service should fetch the files record and check if the file content is the same as the generated zip file
            $this->exclusionListFileRepo->shouldReceive('getFilesForPrefix')->once()->with('tn1')->andReturn([(object)[
                'state_prefix' => 'tn1',
                'img_data' => null, //Not the same as downloaded - should be updated
                'hash' => $hash,
                'img_type' => 'zip'
            ]]);
            
            // Service should check if hash already exists for file in files repository
            $this->exclusionListFileRepo->shouldReceive('contains')->once()->with([
                'state_prefix' => 'tn1',
                'hash' => $hash,
                'img_type' => 'zip'
            ])->andReturn(false);
            
            // Hash for zip file should be inserted since it does not yet exist
            $this->exclusionListFileRepo->shouldReceive('create')->once()->withArgs(function($args) use ($hash){
                return $args['state_prefix'] === 'tn1' &&
                $args['hash'] === $hash &&
                $args['date_last_downloaded'] !== null;
            });

            // Service should fetch the files record matching the prefix and the hash to update with the file contents
            $this->exclusionListFileRepo->shouldReceive('getFilesForPrefixAndHash')->once()->with('tn1', $hash)->andReturn([(object)[
                'state_prefix' => 'tn1',
                'img_data' => null, //Not the same as downloaded - should be updated
                'hash' => $hash,
                'img_type' => 'zip'
            ]]);
        
            $this->exclusionListFileRepo->shouldReceive('update')->once()->with([
                'state_prefix' => 'tn1',
                'hash' => $hash,
            ],[
                'img_data' => file_get_contents($exclusionListTestFileZip)
            ]);
        
            $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');
            
        } finally {
            if ($exclusionListTestFileZip) unlink($exclusionListTestFileZip);
        }
    
    }  
    
    public function testImportFileShouldZipMultipleExclusionListFilesAndNotUpdateTheRepositoryIfRepositoryContentIsEqualToZipFileContents()
    {
        $exclusionListTestFileZip = null;
        
        try {
            $exclusionListTestFile1 = base_path('tests/unit/files/tn1-0.pdf');
            $exclusionListTestFile2 = base_path('tests/unit/files/tn1-0-dummy.pdf');
            $exclusionListTestFileZip = tempnam('tests/unit/files', 'tn1');
            
            create_zip([$exclusionListTestFile1, $exclusionListTestFile2], $exclusionListTestFileZip, true);
            
            // Url should be updated
            $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
            
            // Service should download files
            $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
                return $exclusionList instanceof Tennessee;
            })->andReturn([$exclusionListTestFile1, $exclusionListTestFile2]);
            
            $hash = hash_file('sha256', $exclusionListTestFileZip);
            
            $this->exclusionListStatusHelper->shouldReceive('isUpdateRequired')->once()->with('tn1', $hash)->andReturnTrue();
            
            // For zip files, service should fetch the files record and check if the file content is the same as the generated zip file
            $this->exclusionListFileRepo->shouldReceive('getFilesForPrefix')->once()->with('tn1')->andReturn([(object)[
                'state_prefix' => 'tn1',
                'img_data' => file_get_contents($exclusionListTestFileZip), //same as the downloaded content, hash should not be updated
                'hash' => $hash,
                'img_type' => 'zip'
            ]]);
            
            // Service should check if hash already exists for file in files repository
            $this->exclusionListFileRepo->shouldReceive('contains')->once()->with([
                'state_prefix' => 'tn1',
                'hash' => $hash,
                'img_type' => 'zip'
            ])->andReturn(true);
            
            // Hash for zip file should not be created since it already exists
            $this->exclusionListFileRepo->shouldNotReceive('create');
            
            // File content in repository should not be updated since it is already up-to-date
            $this->exclusionListFileRepo->shouldReceive('update')->once()->withArgs(function($record, $data) use ($hash) {
                return $record == ['state_prefix' => 'tn1', 'hash' => $hash, 'img_type' => 'zip'] && $data['date_last_downloaded'];
            });
            
            // Service should fetch the files matching the prefix and hash to update with the file contents
            $this->exclusionListFileRepo->shouldReceive('getFilesForPrefixAndHash')->once()->with('tn1', $hash)->andReturn([(object)[
                'state_prefix' => 'tn1',
                'img_data' => file_get_contents($exclusionListTestFileZip), //same as the downloaded content, hash should not be updated
                'hash' => $hash,
                'img_type' => 'zip'
            ]]);
            
        
            $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');
            
        } finally {
            if ($exclusionListTestFileZip) unlink($exclusionListTestFileZip);
        }
    
    }   
    
    public function testImportFileShouldNotImportFileContentsIfRecordsUpdateIsNotRequired()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        // Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
    
        // Service should download files
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        $hash = hash_file('sha256', $exclusionListTestFile);
        
        $this->exclusionListStatusHelper->shouldReceive('isUpdateRequired')->once()->with('tn1', $hash)->andReturnFalse();
    
        // Service should not do any list processing since the last imported hash is the same as the hash of the downloaded file
        $this->importFileService->shouldReceive('createListProcessor')->andReturn($this->listProcessorMock);
        $this->listProcessorMock->shouldNotReceive('insertRecords');
    
        // Service should not update exclusion list version since it is already up-to-date
        // Service should check if the if the last imported hash is equal to the downloaded file hash
        $this->exclusionListRepo->shouldNotReceive('update')->withArgs(function($prefix, $data) use ($hash){
            return $prefix === 'tn1' && $data['last_imported_hash'] === $hash && $data['last_imported_date'];
        });
    
        $actual = $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');

        $expected = '{"success":true,"message":"Exclusion list for \'tn1\' is already up-to-date","data":null}';

        // Service should return a response indicating that the records are already up to date
        $this->assertEquals($expected, $actual->getContent());
    }    
    
    public function testRefreshRecordsShouldOnlyImportExclusionListsFlaggedForAutoImport()
    {
        $importFileService = Mockery::mock('App\Services\ImportFileService[importFile]', [
            $this->exclusionListDownloader, 
            $this->exclusionListRepo, 
            $this->exclusionListFileRepo, 
            $this->exclusionListRecordRepo,
            $this->exclusionListStatusHelper
        ])->makePartial();
        
        // All exclusion lists should be queried
        $this->exclusionListRepo->shouldReceive('getAllExclusionLists')->once()->andReturn([
            (object)[
                'id' => 2,
                'prefix' => 'nyomig',
                'accr' => 'NY OMIG',
                'description' => 'New York Office of the Medical Inspector General',
                'import_url' => 'http://www.omig.ny.gov/data/gensplistns.php',
                'is_auto_import' => 1, //Auto-import
                'is_active' => 1
            ]
        ]);
        
        // The url should be searched from the exclusion_lists table
        $this->exclusionListRepo->shouldReceive('find')->once()->with('nyomig')->andReturn([(object)[
                'id' => 2,
                'prefix' => 'nyomig',
                'accr' => 'NY OMIG',
                'description' => 'New York Office of the Medical Inspector General',
                'import_url' => 'http://www.omig.ny.gov/data/gensplistns.php',
                'is_auto_import' => 1, //Auto-import
                'is_active' => 1           
            ]
        ]);
         
        // The import_url should not get updated since the url was taken from the database anyway
        $this->exclusionListRepo->shouldNotReceive('update')->with('nyomig', ['import_url' => 'http://www.omig.ny.gov/data/gensplistns.php']);
        
        // The importFile method of ImportFileService should be called
        $importFileService->shouldReceive('importFile')->once()->with('http://www.omig.ny.gov/data/gensplistns.php', 'nyomig');
        
        $importFileService->refreshRecords();
    } 
    
    public function testRefreshRecordsShouldUpdateFilesOfNonAutoImportListsWithDownloadableContentIfFileVersionIsNotUpToDate()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        // All exclusion lists should be queried
        $this->exclusionListRepo->shouldReceive('getAllExclusionLists')->once()->andReturn([
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
        
        // The urls should be searched from the exclusion_lists table
        $this->exclusionListRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
                'id' => 1,
                'prefix' => 'tn1',
                'accr' => 'TennCare',
                'description' => 'Tennesee State Medicaid Program',
                'import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf',
                'is_auto_import' => '0',
                'is_active' => '1'
            ]
        ]);
        
        // The import_url should not get updated since the url was taken from the database anyway
        $this->exclusionListRepo->shouldNotReceive('update')->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
        
        // Service should download files
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        $hash = hash_file('sha256', $exclusionListTestFile);
        
        // Service should check if hash already exists for file in files repository
        $this->exclusionListFileRepo->shouldReceive('contains')->once()->with([
            'state_prefix' => 'tn1',
            'hash' => $hash,
            'img_type' => 'pdf'
        ])->andReturn(false);
    
        // Service should add hash to files repository
        $this->exclusionListFileRepo->shouldReceive('create')->once()->withArgs(function($args) use ($hash){
            return $args['state_prefix'] === 'tn1' &&
                   $args['hash'] === $hash &&
                   $args['date_last_downloaded'] !== null;
        });
        
        // Service should fetch the files record to update with the file contents
        $this->exclusionListFileRepo->shouldReceive('getFilesForPrefixAndHash')->once()->with('tn1',$hash)->andReturn([(object)[
            'state_prefix' => 'tn1',
            'img_data' => file_get_contents('tests/unit/files/tn1-0-dummy.pdf'), //Not the same as the downloaded - should be updated
            'hash' => $hash,
            'img_type' => 'pdf'
        ]]);
        
        $this->exclusionListFileRepo->shouldReceive('update')->once()->with([
            'state_prefix' => 'tn1',
            'hash' => $hash
        ],[
            'img_data' => file_get_contents($exclusionListTestFile)
        ]);
        
        $this->importFileService->refreshRecords();
    }
    
    public function testRefreshRecordsShouldNotUpdateFilesOfNonAutoImportListsWithDownloadableContentIfFileVersionIsUpToDate()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');
    
        // All exclusion lists should be queried
        $this->exclusionListRepo->shouldReceive('getAllExclusionLists')->once()->andReturn([
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
        
        // The urls should be searched from the exclusion_lists table
        $this->exclusionListRepo->shouldReceive('find')->once()->with('tn1')->andReturn([(object)[
                'id' => 1,
                'prefix' => 'tn1',
                'accr' => 'TennCare',
                'description' => 'Tennesee State Medicaid Program',
                'import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf',
                'is_auto_import' => '0',
                'is_active' => '1'
            ]
        ]);
        
        // The import_url should not get updated since the url was taken from the database anyway
        $this->exclusionListRepo->shouldNotReceive('update')->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
        
        // Service should download files
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);
    
        $hash = hash_file('sha256', $exclusionListTestFile);
        
        // Service should check if hash already exists for file in files repository
        $this->exclusionListFileRepo->shouldReceive('contains')->once()->with([
            'state_prefix' => 'tn1',
            'hash' => $hash,
            'img_type' => 'pdf'
        ])->andReturn(true);
    
        // Service should not add hash to files repository since it already exists
        $this->exclusionListFileRepo->shouldNotReceive('create');
        
        // Service should update date_last_downloaded in files
        $this->exclusionListFileRepo->shouldReceive('update')->once()->withArgs(function($record, $data) use ($hash){
            return $record == ['state_prefix' => 'tn1', 'hash' => $hash, 'img_type' => 'pdf'] && $data['date_last_downloaded'];
        });
        
        // Service should fetch the files record to update with the file contents
        $this->exclusionListFileRepo->shouldReceive('getFilesForPrefixAndHash')->once()->with('tn1', $hash)->andReturn([(object)[
            'state_prefix' => 'tn1',
            'img_data' => file_get_contents($exclusionListTestFile), //Same as the downloaded content, no need to update
            'hash' => $hash,
            'img_type' => 'pdf'
        ]]);
        
        $this->exclusionListFileRepo->shouldNotReceive('update');
        
        $this->importFileService->refreshRecords();
    }    
    
    public function testRefreshRecordsShouldSkipProcessingOfNonAutoImportListsWithNonDownloadableContent()
    {
        // All exclusion lists should be queried
        $this->exclusionListRepo->shouldReceive('getAllExclusionLists')->once()->andReturn([
            (object)[
                'id' => 50,
                'prefix' => 'healthmil',
                'accr' => 'Health Mil',
                'description' => 'Military Health System',
                'import_url' => 'http://www.health.mil/Military-Health-Topics/Access-Cost-Quality-and-Safety/Quality-And-Safety-of-Healthcare/Program-Integrity/Sanctioned-Providers',
                'is_auto_import' => '0',
                'is_active' => '1'
            ]
        ]);
        
        // The urls should be searched from the exclusion_lists table
        $this->exclusionListRepo->shouldReceive('find')->once()->with('healthmil')->andReturn([
            (object)[
                'id' => 50,
                'prefix' => 'healthmil',
                'accr' => 'Health Mil',
                'description' => 'Military Health System',
                'import_url' => 'http://www.health.mil/Military-Health-Topics/Access-Cost-Quality-and-Safety/Quality-And-Safety-of-Healthcare/Program-Integrity/Sanctioned-Providers',
                'is_auto_import' => '0',
                'is_active' => '1'
            ]
        ]);
    
        //The import_url should not be updated
        $this->exclusionListRepo->shouldNotReceive('update')->with('healthmil', ['import_url' => 'http://www.health.mil/Military-Health-Topics/Access-Cost-Quality-and-Safety/Quality-And-Safety-of-Healthcare/Program-Integrity/Sanctioned-Providers']);
    
        // File should not be downloaded (since it is not downloadable)
        $this->exclusionListDownloader->shouldNotReceive('downloadFiles');
        
        // Service should not interact with files repository in any manner
        $this->exclusionListFileRepo->shouldNotReceive('find');
        $this->exclusionListFileRepo->shouldNotReceive('create');
        $this->exclusionListFileRepo->shouldNotReceive('update');
        
        $this->importFileService->refreshRecords();
    }
}
