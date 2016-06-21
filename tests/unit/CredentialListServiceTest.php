<?php

namespace Test\Unit;

use CDM\Test\TestCase;
use App\Services\CredentialListService;

class CredentialListServiceTest extends TestCase
{
    private $service;
    
    public function setUp()
    {
        parent::setUp();
    
        $this->app->withFacades();
        
        $this->service = new CredentialListService();
    }
    
    /**
     * @expectedException App\Services\CredentialListServiceException
     */
    public function testGenerateCredentialListShouldThrowExceptionWhenNoPrefixIsPassed() 
    {
        $this->service->generateCredentialList(null);    
    }
}
