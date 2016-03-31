<?php namespace App\Seeders\NJCredential;

use App\Mappers\NJCredential\EntryMapper;
use App\Repositories\NJCredentialRepository;
use App\Seeders\Seeder;
use App\Seeders\SeederLog;

class CreateSeeder extends Seeder {

	public function __construct(NJCredentialRepository $repository, EntryMapper $mapper, SeederLog $logger)
	{
		$this->repository = $repository;
		$this->mapper = $mapper;
		$this->logger = $logger;
	}

	protected function logError($row)
	{
		$this->logger->error($row, 'nj_credential', 'seed');
	}

	protected function persistRecord($record)
	{
		$this->repository->create($record);
	}
}
