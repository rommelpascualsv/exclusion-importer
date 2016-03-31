<?php namespace App\Console\Commands\NJCredential;

use App\Console\Commands\BaseSeed;
use App\Seeders\NJCredential\CreateSeeder;

class Seed extends BaseSeed {

	protected $name = 'njcredential:seed';

	protected $description = 'Seed the NJ Credential Database in mongo nj_credential collection';

	protected $database = 'NJ Credential Database';

	public function __construct(CreateSeeder $seeder)
	{
		parent::__construct();
		$this->seeder = $seeder;
	}

}
