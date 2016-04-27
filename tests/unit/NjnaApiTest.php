<?php

use App\Repositories\NjnaRepository;

class NjnaApiTest extends TestCase 
{
    public function setUp()
    {
        parent::setUp();

        $this->app->make('MongoDB')->drop();
        $repository = $this->app->make(NjnaRepository::class);

        $record = ArrayFactory::create('NjnaRecord');
        $repository->create($record);
    }

    public function test_get_request_returns_njna_record()
    {
        $response = $this->call('GET', '/njna/NA200016878');
        $expected = json_encode(ArrayFactory::create('NjnaRecord'));
        $actual = $response->getContent();
        $this->assertEquals($expected, $actual);
    }

    public function test_get_request_returns_404_for_record_not_found()
    {
        $response = $this->call('GET', '/njna/NA124143531');
        $this->assertResponseStatus(404);
    }

    public function tearDown()
    {
        $repository = $this->app->make(NjnaRepository::class);
        $repository->clear();
        parent::tearDown();
    }
}
