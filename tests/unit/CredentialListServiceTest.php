<?php

namespace Test\Unit;

use CDM\Test\TestCase;
use App\Services\CredentialListService;
use Mockery;
use App\Import\CredentialLists\NJCredentialFileMaker;

class CredentialListServiceTest extends TestCase
{
    private $service;
    private $credentialDBRepo;
    private $credentialFileMakerFactory;
    
    public function setUp()
    {
        parent::setUp();
    
        $this->app->withFacades();
        
        $this->credentialDBRepo = Mockery::mock('App\Repositories\CredentialDatabaseRepository')->makePartial();
        $this->credentialFileMakerFactory = Mockery::mock('App\Import\CredentialLists\CredentialFileMakerFactory')->makePartial();
        
        $this->service = new CredentialListService($this->credentialDBRepo,
            $this->credentialFileMakerFactory
        );
    }
    
    /**
     * @expectedException App\Services\CredentialListServiceException
     */
    public function testGenerateCredentialListShouldThrowExceptionWhenNoPrefixIsPassed() 
    {
        $this->service->generateCredentialListFile(null);    
    }
    
    /**
     * @expectedException App\Services\CredentialListServiceException
     */
    public function testGenerateCredentialListShouldThrowExceptionWhenDestinationFilePathForPrefixIsNotSpecifiedInArgsOrRepo()
    {
        $this->credentialDBRepo->shouldReceive('find')->once()->with('njcredential')->andReturn(null);
        
        $this->service->generateCredentialListFile('njcredential');
    }
    
    public function testGenerateCredentialListShouldUseDestinationPathSpecifiedInArgsToInstantiateCredentialFileMaker()
    {
        $this->credentialDBRepo->shouldNotReceive('find');
        
        $this->credentialFileMakerFactory->shouldReceive('createCredentialFileMaker')
            ->once()
            ->with('njcredential', 'njcredential.csv')
            ->andReturn(Mockery::mock('App\Import\CredentialLists\NJCredentialFileMaker')->shouldIgnoreMissing());
        
        $this->service->generateCredentialListFile('njcredential', 'njcredential.csv');
    }  
    
    public function testGenerateCredentialListShouldUseDestinationPathSpecifiedInRepoToInstantiateCredentialFileMaker()
    {
        $this->credentialDBRepo->shouldReceive('find')->once()->with('njcredential')->andReturn((object)[
            'storage_path' => 'repo-storage/njcredential.csv'     
        ]);
    
        $this->credentialFileMakerFactory->shouldReceive('createCredentialFileMaker')
        ->once()
        ->with('njcredential', 'repo-storage/njcredential.csv')
        ->andReturn(Mockery::mock('App\Import\CredentialLists\NJCredentialFileMaker')->shouldIgnoreMissing());
    
        $this->service->generateCredentialListFile('njcredential');
    }  
    
    /**
     * @expectedException App\Services\CredentialListServiceException
     */
    public function testGenerateCredentialListShouldThrowExceptionIfCredentialFileMakerCannotBeCreated()
    {
        $this->credentialFileMakerFactory->shouldReceive('createCredentialFileMaker')
        ->once()
        ->with('unknown_prefix', 'test-unknown-prefix.csv')
        ->andReturnNull();
    
        $this->service->generateCredentialListFile('unknown_prefix', 'test-unknown-prefix.csv');
    }    
}
