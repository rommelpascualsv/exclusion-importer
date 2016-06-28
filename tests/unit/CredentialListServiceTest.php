<?php

namespace Test\Unit;

use App\Import\CredentialLists\CredentialFileMaker;
use App\Import\CredentialLists\CredentialFileMakerFactory;
use App\Repositories\FileRepository;
use App\Seeders\Seeder;
use App\Seeders\SeederFactory;
use CDM\Test\TestCase;
use App\Services\CredentialListService;
use League\Flysystem\Exception;
use Mockery;
use App\Import\CredentialLists\NJCredentialFileMaker;

class CredentialListServiceTest extends TestCase
{
    /**
     * @var CredentialListService
     */
    private $service;
    private $credentialFileMakerFactory;
    private $fileRepo;
    private $seederFactory;
    private $mockCredentialFileMaker;
    private $mockSeeder;
    private $tempFile;

    public function setUp()
    {
        parent::setUp();
    
        $this->app->withFacades();
        
        $this->credentialFileMakerFactory = Mockery::mock(CredentialFileMakerFactory::class)->makePartial();
        $this->fileRepo = Mockery::mock(FileRepository::class)->makePartial();
        $this->seederFactory = Mockery::mock(SeederFactory::class)->makePartial();
        $this->mockCredentialFileMaker = Mockery::mock(CredentialFileMaker::class)->makePartial();
        $this->mockSeeder = Mockery::mock(Seeder::class);

        $this->service = new CredentialListService(
            $this->credentialFileMakerFactory,
            $this->fileRepo,
            $this->seederFactory
        );

        $this->tempFile = tempnam(sys_get_temp_dir(), str_random(4));
    }

    public function tearDown()
    {
        if ($this->tempFile && file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }

        parent::tearDown();

    }

    public function testGenerateCredentialListShouldInsertCredentialFileToFilesRepoIfFileDoesNotYetExistInRepo()
    {
        $this->credentialFileMakerFactory->shouldReceive('createCredentialFileMaker')->once()
            ->with('njcredential', 'http://www.njcredential.com')->andReturn($this->mockCredentialFileMaker);

        $this->mockCredentialFileMaker->shouldReceive('buildFile')->once()->with($this->tempFile);

        $hash = hash_file(CredentialListService::FILE_HASH_ALGO, $this->tempFile);
        $fileContents = file_get_contents($this->tempFile);

        $record = [
            'state_prefix' => 'njcredential',
            'hash' => $hash,
            'img_type' => 'csv',
            'img_data' => $fileContents
        ];

        $this->fileRepo->shouldReceive('contains')->once()->with($record)->andReturnFalse();

        $this->fileRepo->shouldReceive('create')->once()->withArgs(function($args) use ($hash, $fileContents){
            return $args['state_prefix'] === 'njcredential' &&
                   $args['hash'] === $hash &&
                   $args['img_data'] === $fileContents &&
                   $args['date_last_downloaded'] !== null;
        });

        $this->fileRepo->shouldNotReceive('update');

        $actual = $this->service->createCredentialDatabaseFile('njcredential', 'http://www.njcredential.com', $this->tempFile);
        $expected = $hash;

        $this->assertEquals($expected, $actual);
    }

    public function testGenerateCredentialListShouldJustUpdateLastDownloadDateOfCrednentialFileIfFileAlreadyExistsInRepo()
    {
        $this->credentialFileMakerFactory->shouldReceive('createCredentialFileMaker')->once()
            ->with('njcredential', 'http://www.njcredential.com')->andReturn($this->mockCredentialFileMaker);

        $this->mockCredentialFileMaker->shouldReceive('buildFile')->once()->with($this->tempFile);

        $hash = hash_file(CredentialListService::FILE_HASH_ALGO, $this->tempFile);
        $fileContents = file_get_contents($this->tempFile);

        $record = [
            'state_prefix' => 'njcredential',
            'hash' => $hash,
            'img_type' => 'csv',
            'img_data' => $fileContents
        ];

        $this->fileRepo->shouldReceive('contains')->once()->with($record)->andReturnTrue();

        $this->fileRepo->shouldReceive('update')->once()->withArgs(function($arg1, $arg2) use ($record){
            return $arg1 == $record && $arg2['date_last_downloaded'] !== null;
        });

        $this->fileRepo->shouldNotReceive('create');

        $actual = $this->service->createCredentialDatabaseFile('njcredential', 'http://www.njcredential.com', $this->tempFile);
        $expected = $hash;

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Unable to write to destination file
     */
    public function testGenerateCredentialListWithExceptionThrownOnCreationOfCredentialFile()
    {
        $this->credentialFileMakerFactory->shouldReceive('createCredentialFileMaker')->once()
            ->with('njcredential', 'http://www.njcredential.com')->andReturn($this->mockCredentialFileMaker);

        $this->mockCredentialFileMaker->shouldReceive('buildFile')->once()->andThrow(\RuntimeException::class, 'Unable to write to destination file');

        $this->service->createCredentialDatabaseFile('njcredential', 'http://www.njcredential.com', $this->tempFile);
    }


    public function testSeedShouldReturnSeederResults()
    {
        $this->seederFactory->shouldReceive('createSeeder')->once()->with('njcredential')->andReturn($this->mockSeeder);

        $expected = [
            'succeeded' => 12345,
            'failed' => 3
        ];

        $this->mockSeeder->shouldReceive('seed')->once()->with($this->tempFile)->andReturn($expected);

        $actual = $this->service->seed('njcredential', $this->tempFile);

        $this->assertEquals($expected, $actual);
        
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Unable to parse file
     */
    public function testSeedWithExceptionThrownOnSeederSeeding()
    {
        $this->seederFactory->shouldReceive('createSeeder')->once()->with('njcredential')->andReturn($this->mockSeeder);

        $this->mockSeeder->shouldReceive('seed')->once()->with($this->tempFile)->andThrow(\RuntimeException::class, 'Unable to parse file');

        $this->service->seed('njcredential', $this->tempFile);
    }
}
