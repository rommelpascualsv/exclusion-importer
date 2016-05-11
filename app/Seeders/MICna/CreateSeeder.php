<?php namespace App\Seeders\MICna;

use App\Repositories\MICnaRepository;
use App\Seeders\Seeder;
use App\Seeders\SeederLog;
use App\Mappers\MICna\EntryMapper;

class CreateSeeder extends Seeder
{
    public function __construct(MICnaRepository $micnaRepository, EntryMapper $entryMapper, SeederLog $logger)
    {
        $this->repository = $micnaRepository;
        $this->mapper = $entryMapper;
        $this->logger = $logger;
    }

    protected function prepare()
    {
        $this->repository->clear();
    }

    protected function logError($row)
    {
        $this->logger->error($row, 'micna', 'seed');
    }

    protected function persistRecord($record)
    {
        $this->repository->create($record);
    }
}
