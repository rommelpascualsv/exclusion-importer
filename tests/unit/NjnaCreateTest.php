<?php

use App\Repositories\NjnaRepository;

class NjnaCreateTest extends MongoTestCase 
{
	public function test_njna_record_creates_record_in_database()
	{
		$repository = $this->app->make(NjnaRepository::class);

		$njnaRecord = ArrayFactory::create('NjnaRecord');
		$repository->create($njnaRecord);

		$expected = ArrayFactory::create('NjnaRecord');
		$actual = $repository->find($njnaRecord['certificate_number']);

		$this->assertEquals($expected, $actual);
	}
}
