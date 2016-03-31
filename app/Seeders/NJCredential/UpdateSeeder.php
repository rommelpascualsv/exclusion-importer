<?php

namespace App\Seeders\NJCredential;

use App\Mappers\NJCredential\EntryMapper;
use App\Repositories\NJCredentialRepository;
use App\Seeders\Seeder;
use App\Seeders\SeederLog;

class UpdateSeeder extends Seeder 
{
	public function __construct(NJCredentialRepository $repository, EntryMapper $mapper, SeederLog $logger)
	{
		$this->repository = $repository;
		$this->mapper = $mapper;
		$this->logger = $logger;
	}

	protected function logError($row)
	{
		$this->logger->error($row, 'nj_credential', 'update');
	}

	protected function persistRecord($record)
	{
		$this->repository->createOrUpdate($record);
	}
}
