<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

abstract class BaseClear extends Command {

    protected $repository;

    public function fire()
    {
        $this->repository->clear();
        $this->info($this->database . ' database cleared');
    }
}
