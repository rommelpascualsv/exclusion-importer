<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use App\Services\DateValidator;

abstract class BaseSeed extends Command {

    /**
     * @var \App\Seeders\Seeder
     */
    protected $seeder;

	/**
	 * @var string the name of the database
	 */
	protected $database;

    public function fire()
    {
        $file = $this->argument('file');

        if ($this->argument('date') && ! DateValidator::validateString($this->argument('date'))) {
            $this->info('Date errors. Specify date as mm/dd/yyyy');
            return;
        }

        if ($this->name == 'nppes:optout') {
            $this->info($this->database . ' opt out seeding started');
            $results = $this->seeder->seedOptout($file);
        } else {
            $this->info($this->database . ' seeding started');
            $results = $this->seeder->seed($file, $this->argument('date'));
        }

        $this->outputResults($results);
    }

    protected function outputResults($results)
    {
        $this->info($results['succeeded'] . ' entries successfully created');
        $this->error($results['failed'] . ' entries failed to be parsed');

        if (isset($results['matched'])) {
            $this->info($results['matched'] . ' entries successfully matched');
        }

        $this->info($this->database . ' seeding finished');
    }

    protected function getArguments()
    {
        return [
            ['file', InputArgument::REQUIRED, 'The CSV file to parse'],
            ['date', InputArgument::OPTIONAL, 'The as of date for the data (mm/dd/yyyy)']
        ];
    }
}
