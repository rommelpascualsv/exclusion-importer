<?php

use App\Repositories\NppesRepository;
use CDM\Test\MongoTestCase;

class NppesDeactivateTest extends MongoTestCase
{
	public function test_nppes_record_sets_deactiviation_date_in_database()
	{
		$repository = $this->app->make(NppesRepository::class);

		// Get Nppes and enter it into the database
		$nppesRecord = ArrayFactory::create('NppesRecord');
		$repository->create($nppesRecord);

		// Get Nppes deactiviation and update the database with it
		$deactivationRecord = ArrayFactory::create('NppesDeactivationRecord');
		$repository->update($deactivationRecord);

		// Retrieve original Nppes from the database
		$actual = $repository->find($nppesRecord['npi']);

		$expected = ArrayFactory::create('NppesRecord', ['deactivation_date' => '10/10/10']);

		$this->assertEquals($expected, $actual);
	}
}
