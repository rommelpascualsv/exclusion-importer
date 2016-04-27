<?php

use App\Repositories\NppesRepository;

class NppesUpdateTest extends MongoTestCase 
{
	public function test_nppes_record_updates_record_database()
	{
		$repository = $this->app->make(NppesRepository::class);

		// Get Nppes and enter it into the database
		$nppesRecord = ArrayFactory::create('NppesRecord');
		$repository->create($nppesRecord);

		// Get Nppes with updated practice city name and update the database with it
		$updateRecord = ArrayFactory::create('NppesRecord', ['address' => ['practice' => ['city_name' => 'CAMBRIDGE']]]);
		$repository->createOrUpdate($updateRecord);

		// Retrieve the originally entered Nppes from the database
		$actual = $repository->find($nppesRecord['npi']);

		// Ensure originally entered Nppes is equal to the updated Nppes
		$this->assertEquals($updateRecord, $actual);
	}
}
