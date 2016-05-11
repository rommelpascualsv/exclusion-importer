<?php namespace App\Seeders\Nppes;

use App\Seeders\Seeder;
use App\Seeders\SeederLog;
use App\Repositories\NppesRepository;
use App\Mappers\Nppes\ProviderMapper;

class CreateSeeder extends Seeder
{
    public function __construct(NppesRepository $nppesRepository, ProviderMapper $providerMapper, SeederLog $logger)
    {
        $this->repository = $nppesRepository;
        $this->mapper = $providerMapper;
        $this->logger = $logger;
    }

    protected function prepare()
    {
        $this->repository->clear();
    }

    protected function logError($row)
    {
        $this->logger->error($row, 'nppes', 'seed');
    }

    protected function persistRecord($record)
    {
        $this->repository->create($record);
    }
}
