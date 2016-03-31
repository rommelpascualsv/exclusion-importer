<?php namespace App\Seeders\Nppes;

use App\Seeders\Seeder;
use App\Seeders\SeederLog;
use App\Repositories\NppesRepository;
use App\Mappers\Nppes\DeactivationMapper;

class DeactivationSeeder extends Seeder {

	public function __construct(NppesRepository $nppesRepository, DeactivationMapper $deactivationMapper, SeederLog $logger)
	{
		$this->repository = $nppesRepository;
		$this->mapper = $deactivationMapper;
		$this->logger = $logger;
	}

	protected function logError($row)
	{
		$this->logger->error($row, 'nppes', 'deactivation');
	}

	protected function persistRecord($record)
	{
		$this->repository->update($record);
	}
}
