<?php namespace App\Console\Commands\NJCredential;


use App\Console\Commands\BaseSeed;
use App\Seeders\NJCredential\UpdateSeeder;

class Update extends BaseSeed 
{
	protected $name = 'njcredential:update';

	protected $description = 'Update the NJ Credential Database in mongo nj_credential collection';

	protected $database = 'NJ Credential Database';

	public function __construct(UpdateSeeder $seeder)
	{
		parent::__construct();
		$this->seeder = $seeder;
	}

	protected function outputResults($results)
	{
		$this->info($results['succeeded'] . ' entries successfully updated or created');
		$this->error($results['failed'] . ' entries failed to be parsed');
		$this->info('Finished updating nj_credential database');
	}
}
