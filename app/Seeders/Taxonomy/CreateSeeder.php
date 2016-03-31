<?php namespace App\Seeders\Taxonomy;

use App\Seeders\Seeder;
use App\Seeders\SeederLog;
use App\Repositories\TaxonomyRepository;
use App\Mappers\Taxonomy\EntryMapper;

class CreateSeeder extends Seeder  {

	public function __construct(TaxonomyRepository $taxonomyRepository, EntryMapper $entryMapper, SeederLog $logger)
	{
		$this->repository = $taxonomyRepository;
		$this->mapper = $entryMapper;
		$this->logger = $logger;
	}

	protected function prepare()
	{
		$this->repository->clear();
	}

	protected function logError($row)
	{
		$this->logger->error($row, 'taxonomy', 'seed');
	}

	protected function persistRecord($record)
	{
		$this->repository->create($record);
	}
}
