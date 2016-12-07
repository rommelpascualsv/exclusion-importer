<?php

namespace Test\Unit;

use App\Import\CredentialLists\CredentialFileMaker;
use App\Import\CredentialLists\CredentialFileMakerFactory;
use App\Seeders\Seeder;
use App\Seeders\SeederFactory;
use App\Services\CredentialListService;
use App\Services\FileHelper;
use CDM\Test\TestCase;
use Mockery;

class CredentialListServiceTest extends TestCase
{
    private $service;
    private $credentialFileMakerFactory;
    private $fileHelper;
    private $seederFactory;
    private $mockCredentialFileMaker;
    private $mockSeeder;
    private $tempFile;

    public function setUp()
    {
        parent::setUp();
    
        $this->app->withFacades();
        
        $this->credentialFileMakerFactory = Mockery::mock(CredentialFileMakerFactory::class)->makePartial();
        $this->seederFactory = Mockery::mock(SeederFactory::class)->makePartial();
        $this->fileHelper = Mockery::mock(FileHelper::class)->makePartial();

        $this->mockCredentialFileMaker = Mockery::mock(CredentialFileMaker::class)->makePartial();
        $this->mockSeeder = Mockery::mock(Seeder::class);

        $this->service = new CredentialListService(
            $this->credentialFileMakerFactory,
            $this->seederFactory,
            $this->fileHelper
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

    public function testGenerateCredentialListShouldBuildCredentialFileAndPersistItInFilesRepo()
    {
        $this->credentialFileMakerFactory->shouldReceive('createCredentialFileMaker')->once()
            ->with('njcredential', 'http://www.njcredential.com')->andReturn($this->mockCredentialFileMaker);

        $this->mockCredentialFileMaker->shouldReceive('buildFile')->once()->with($this->tempFile);

        $hash = hash_file(FileHelper::FILE_HASH_ALGO, $this->tempFile);

        $this->fileHelper->shouldReceive('createAndSaveFileHash')->once()->with($this->tempFile, 'csv', 'njcredential')->andReturn($hash);

        $this->fileHelper->shouldReceive('saveFileContents')->once()->with($this->tempFile, $hash, 'njcredential')->andReturnTrue();

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
