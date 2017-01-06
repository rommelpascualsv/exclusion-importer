<?php

namespace Test\Unit;

use App\Import\Lists\Tennessee;
use App\Import\Service\ListProcessor;
use App\Import\ImportStats;
use App\Repositories\ExclusionListRecordRepository;
use App\Repositories\ExclusionListRepository;
use App\Services\ExclusionListHttpDownloader;
use App\Services\ExclusionListStatusHelper;
use App\Services\FileHelper;
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
    private $exclusionListRecordRepo;
    private $exclusionListStatusHelper;
    private $fileHelper;
    
    private $listProcessorMock;
    
    public function setUp()
    {
        parent::setUp();
        
        $this->app->withFacades();
        
        $this->exclusionListDownloader = Mockery::mock(ExclusionListHttpDownloader::class)->makePartial();
        $this->exclusionListRepo = Mockery::mock(ExclusionListRepository::class)->makePartial();
        $this->exclusionListRecordRepo = Mockery::mock(ExclusionListRecordRepository::class)->makePartial();
        $this->exclusionListStatusHelper = Mockery::mock(ExclusionListStatusHelper::class)->makePartial();
        $this->fileHelper = Mockery::mock(FileHelper::class)->makePartial();
        
        $this->listProcessorMock = Mockery::mock(ListProcessor::class)->shouldIgnoreMissing();
        
        $this->importFileService = Mockery::mock(ImportFileService::class, [
            $this->exclusionListDownloader, 
            $this->exclusionListRepo,
            $this->exclusionListRecordRepo,
            $this->fileHelper,
            $this->exclusionListStatusHelper
        ])->makePartial();
        
        $this->importFileService->shouldAllowMockingProtectedMethods();
        
        $this->withoutEvents();
    }

    public function testImportFileShouldRespondWithErrorIfUrlIsEmpty()
    {
        $actual = $this->importFileService->importFile('', 'nyomig');
        $expected = '{"success":false,"message":"No URL was specified for : nyomig","data":null}';
        $this->assertEquals($expected, $actual->getContent());
    }
    
    public function testImportFileShouldRespondWithErrorMessageIfFileDownloadFails()
    {
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);
    
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andThrow(new \Exception('Error downloading file'));

        $this->exclusionListStatusHelper->shouldNotReceive('isUpdateRequired');

        $this->fileHelper->shouldNotReceive('createAndSaveFileHash');
        $this->fileHelper->shouldNotReceive('saveFileContents');
    
        $this->importFileService->shouldNotReceive('createListProcessor');
        $this->listProcessorMock->shouldNotReceive('insertRecords');
    
        $actual = $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');

        $expected = '{"success":false,"message":"Error importing exclusion list for \'tn1\' : Error downloading file","data":null}';
        
        $this->assertEquals($expected, $actual->getContent());
    }
    
    public function testImportFileShouldSaveDownloadedFileToTheFilesRepoAndImportItsContentsIfAnUpdateIsRequired()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');

        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);

        $this->exclusionListDownloader->shouldReceive('supports')->once()->with('pdf')->andReturnTrue();

        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);

        $hash = hash_file('sha256', $exclusionListTestFile);

        // Service should check if hash already exists for file in files repository
        $this->fileHelper->shouldReceive('zipMultiple')->once()->with([$exclusionListTestFile])->andReturn($exclusionListTestFile);
        $this->fileHelper->shouldReceive('createAndSaveFileHash')->once()->with($exclusionListTestFile, 'pdf', 'tn1')->andReturn($hash);
        $this->fileHelper->shouldReceive('saveFileContents')->once()->with($exclusionListTestFile, $hash, 'tn1')->andReturnTrue();

        $this->exclusionListStatusHelper->shouldReceive('isUpdateRequired')->once()->with('tn1', $hash)->andReturnTrue();
        
        $this->importFileService->shouldReceive('createListProcessor')->once()->withArgs(function($arg1){
            return $arg1 instanceof Tennessee;
        })->andReturn($this->listProcessorMock);

        $this->listProcessorMock->shouldReceive('insertRecords')->once();

        $importStats = (new ImportStats())->setAdded(1)->setDeleted(2)->setPreviousRecordCount(3)->setCurrentRecordCount(4);

        $this->exclusionListRecordRepo->shouldReceive('getImportStats')->once()->with('tn1')->andReturn($importStats);

        $this->exclusionListRepo->shouldReceive('update')->once()->withArgs(function($arg1, $arg2) use ($hash, $importStats) {
            return $arg1 === 'tn1'
                && $arg2['last_imported_hash'] === $hash
                && ! empty($arg2['last_imported_date'])
                && $arg2['last_import_stats'] == json_encode($importStats);
        });

        $actual = $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');

        $actualData = $actual->getData(true);

        $this->assertTrue($actualData['success']);
        $this->assertEquals('tn1', $actualData['data']['prefix']);
        $this->assertEquals($hash, $actualData['data']['importResults']['fileHash']);
    }

    public function testImportFileShouldNotImportDownloadedFileIfAnUpdateIsNotRequired()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');

        // Url should be updated
        $this->exclusionListRepo->shouldReceive('update')->once()->with('tn1', ['import_url' => 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf']);

        $this->exclusionListDownloader->shouldReceive('supports')->once()->with('pdf')->andReturnTrue();

        // Service should download files
        $this->exclusionListDownloader->shouldReceive('downloadFiles')->once()->withArgs(function($exclusionList){
            return $exclusionList instanceof Tennessee;
        })->andReturn([$exclusionListTestFile]);

        $hash = hash_file('sha256', $exclusionListTestFile);

        // Service should check if hash already exists for file in files repository
        $this->fileHelper->shouldReceive('zipMultiple')->once()->with([$exclusionListTestFile])->andReturn($exclusionListTestFile);
        $this->fileHelper->shouldReceive('createAndSaveFileHash')->once()->with($exclusionListTestFile, 'pdf', 'tn1')->andReturn($hash);
        $this->fileHelper->shouldReceive('saveFileContents')->once()->with($exclusionListTestFile, $hash, 'tn1')->andReturnFalse();

        $this->exclusionListStatusHelper->shouldReceive('isUpdateRequired')->once()->with('tn1', $hash)->andReturnFalse();

        $this->importFileService->shouldNotReceive('createListProcessor');

        $this->listProcessorMock->shouldNotReceive('insertRecords');

        $this->exclusionListRecordRepo->shouldNotReceive('getImportStats');

        $this->exclusionListRepo->shouldNotReceive('update');

        $actual = $this->importFileService->importFile('http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf', 'tn1');

        $expected = (object)[
            'success' => true,
            'message' => 'Exclusion list for \'tn1\' is already up-to-date',
            'data' => null
        ];

        $this->assertEquals($expected, $actual->getData());
    }

    public function testRefreshRecordsShouldOnlyImportExclusionListsFlaggedForAutoImport()
    {
        $importFileService = Mockery::mock('App\Services\ImportFileService[importFile]', [
            $this->exclusionListDownloader,
            $this->exclusionListRepo,
            $this->exclusionListRecordRepo,
            $this->fileHelper,
            $this->exclusionListStatusHelper
        ])->makePartial();

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

        $importFileService->shouldReceive('importFile')->once()->with('http://www.omig.ny.gov/data/gensplistns.php', 'nyomig');

        $importFileService->refreshRecords();
    }

    public function testRefreshRecordsShouldJustUpdateFilesOfNonAutoImportListsWithDownloadableContent()
    {
        $exclusionListTestFile = base_path('tests/unit/files/tn1-0.pdf');

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

        $this->fileHelper->shouldReceive('zipMultiple')->once()->with([$exclusionListTestFile])->andReturn($exclusionListTestFile);

        $this->fileHelper->shouldReceive('createAndSaveFileHash')->once()->with($exclusionListTestFile, 'pdf', 'tn1')->andReturn($hash);

        $this->fileHelper->shouldReceive('saveFileContents')->once()->with($exclusionListTestFile, $hash, 'tn1')->andReturnTrue();

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

        $this->exclusionListDownloader->shouldNotReceive('downloadFiles');

        $this->fileHelper->shouldNotReceive('zipMultiple');
        $this->fileHelper->shouldNotReceive('createAndSaveFileHash');
        $this->fileHelper->shouldNotReceive('saveFileContents');

        $this->importFileService->refreshRecords();
    }
}
