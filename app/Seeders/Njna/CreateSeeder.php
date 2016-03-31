<?php namespace App\Seeders\Njna;

use App\Seeders\Seeder;
use App\Seeders\SeederLog;
use App\Repositories\NjnaRepository;
use App\Mappers\Njna\EntryMapper;

class CreateSeeder extends Seeder 
{
	public function __construct(NjnaRepository $njnaRepository, EntryMapper $entryMapper, SeederLog $logger)
	{
		$this->repository = $njnaRepository;
		$this->mapper = $entryMapper;
		$this->logger = $logger;
	}

	protected function prepare()
	{
		$this->repository->clear();
	}

	protected function logError($row)
	{
		$this->logger->error($row, 'njna', 'seed');
	}

	protected function persistRecord($record)
	{
		$this->repository->create($record);
	}
}
