<?php

namespace Test\Unit;


use App\Console\Commands\BaseImportCredentials;
use App\Repositories\CredentialDatabaseRepository;
use App\Services\CredentialListService;
use CDM\Test\TestCase;
use Mockery;

class BaseImportCredentialsTest extends TestCase
{
    private $command;
    private $credentialListService;
    private $credentialDBRepo;
    private $mockCredentialDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->credentialListService = Mockery::mock(CredentialListService::class);
        $this->credentialDBRepo = Mockery::mock(CredentialDatabaseRepository::class);
        $this->mockCredentialDatabase = Mockery::mock('App\CredentialDatabase\CredentialDatabase[save]');

        $this->command = $this->getMockForAbstractClass(BaseImportCredentials::class,[
            $this->credentialListService,
            $this->credentialDBRepo
        ], '', true, true, true, ['getPrefix']);
    }

    public function testFireShouldGenerateCredentialListFileThenUseItToSeedCredentialsIfCredentialDatabaseIsSetToAutoSeed()
    {
        $mockCredentialDatabase = $this->mockCredentialDatabase;
        $mockCredentialDatabase->setId(1);
        $mockCredentialDatabase->setPrefix('njcredential');
        $mockCredentialDatabase->setDescription('NJ Credential database');
        $mockCredentialDatabase->setSourceUri('http://www.njcredential.com');
        $mockCredentialDatabase->setAutoSeed(1);
        $mockCredentialDatabase->setLastImportHash(null);
        $mockCredentialDatabase->setLastImportDate(null);


        $this->credentialDBRepo->shouldReceive('find')->once()->with('njcredential')->andReturn($mockCredentialDatabase);

        $this->credentialListService->shouldReceive('createCredentialDatabaseFile')->once()->withArgs(function($prefix, $sourceUri, $destinationFile){
            return $prefix === 'njcredential' && $sourceUri === 'http://www.njcredential.com' && file_exists($destinationFile);
        })->andReturn('mock_hash');

        $this->credentialListService->shouldReceive('seed')->once()->withArgs(function($prefix, $destinationFile){
            return $prefix === 'njcredential' && file_exists($destinationFile);
        });

        $mockCredentialDatabase->shouldReceive('setLastImportHash->setLastImportDate');
        $mockCredentialDatabase->shouldReceive('save')->once();

        $this->command->expects($this->once())->method('getPrefix')->will($this->returnValue('njcredential'));

        $this->command->fire();

        $this->assertEquals('mock_hash', $mockCredentialDatabase->getLastImportHash());
    }

    public function testFireShouldGenerateCredentialListFileButNotSeedCredentialsIfCredentialDatabaseIsNotSetToAutoSeed()
    {
        $mockCredentialDatabase = $this->mockCredentialDatabase;
        $mockCredentialDatabase->setId(1);
        $mockCredentialDatabase->setPrefix('njcredential');
        $mockCredentialDatabase->setDescription('NJ Credential database');
        $mockCredentialDatabase->setSourceUri('http://www.njcredential.com');
        $mockCredentialDatabase->setAutoSeed(0);
        $mockCredentialDatabase->setLastImportHash(null);
        $mockCredentialDatabase->setLastImportDate(null);

        $this->credentialDBRepo->shouldReceive('find')->once()->with('njcredential')->andReturn($mockCredentialDatabase);

        $this->credentialListService->shouldReceive('createCredentialDatabaseFile')->once()->withArgs(function($prefix, $sourceUri, $destinationFile){
            return $prefix === 'njcredential' && $sourceUri === 'http://www.njcredential.com' && file_exists($destinationFile);
        })->andReturn('mock_hash');

        $this->credentialListService->shouldNotReceive('seed');

        $mockCredentialDatabase->shouldNotReceive('setLastImportHash->setLastImportDate');
        $mockCredentialDatabase->shouldNotReceive('save');

        $this->command->expects($this->once())->method('getPrefix')->will($this->returnValue('njcredential'));

        $this->command->fire();

        $this->assertNull($mockCredentialDatabase->getLastImportHash());
    }

    public function testFireShouldGenerateCredentialListFileButNotSeedCredentialsIfLastImportHashIsEqualToFileHash()
    {
        $mockCredentialDatabase = $this->mockCredentialDatabase;
        $mockCredentialDatabase->setId(1);
        $mockCredentialDatabase->setPrefix('njcredential');
        $mockCredentialDatabase->setDescription('NJ Credential database');
        $mockCredentialDatabase->setSourceUri('http://www.njcredential.com');
        $mockCredentialDatabase->setAutoSeed(1);
        $mockCredentialDatabase->setLastImportHash('mock_hash');
        $mockCredentialDatabase->setLastImportDate(date('Y-m-d'));

        $this->credentialDBRepo->shouldReceive('find')->once()->with('njcredential')->andReturn($mockCredentialDatabase);

        $this->credentialListService->shouldReceive('createCredentialDatabaseFile')->once()->withArgs(function($prefix, $sourceUri, $destinationFile){
            return $prefix === 'njcredential' && $sourceUri === 'http://www.njcredential.com' && file_exists($destinationFile);
        })->andReturn('mock_hash');

        $this->credentialListService->shouldNotReceive('seed');

        $mockCredentialDatabase->shouldNotReceive('setLastImportHash->setLastImportDate');
        $mockCredentialDatabase->shouldNotReceive('save');

        $this->command->expects($this->once())->method('getPrefix')->will($this->returnValue('njcredential'));

        $this->command->fire();

        $this->assertEquals('mock_hash', $mockCredentialDatabase->getLastImportHash());
    }
}
