<?php

use App\Repositories\TaxonomyRepository;

class TaxonomyCreateTest extends \CDM\Test\MongoTestCase
{
	public function test_taxonomy_record_creates_record_in_database()
	{
		$repository = $this->app->make(TaxonomyRepository::class);

		$taxonomyRecord = ArrayFactory::create('TaxonomyRecord');
		$repository->create($taxonomyRecord);

		$expected = ArrayFactory::create('TaxonomyRecord');
		$actual = $repository->find($taxonomyRecord['code']);

		$this->assertEquals($expected, $actual);
	}
}
