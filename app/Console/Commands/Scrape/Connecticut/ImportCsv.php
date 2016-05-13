<?php

namespace App\Console\Commands\Scrape\Connecticut;

use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\Scrapers\Connecticut\CsvImporter;
use Illuminate\Console\Command;
use App\Import\Scrape\Scrapers\Connecticut\Data\CsvDir;

/**
 * Command class that handles the refreshing of records in Files table.
 */
class ImportCsv extends Command 
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'scrape_connecticut:import_csv';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import downloaded csvs to the database';
	
	/**
	 * Execute the console command.
	 * @param ScrapeFilesystemInterface
	 */
	public function handle(ScrapeFilesystemInterface $filesystem)
	{
	    $importData = CsvDir::getDataFromFilesystem($filesystem);
	    $csvImporter = new CsvImporter($importData, app('db')->connection());
	    
	    $this->line('Importing data from ' . count($importData) . ' csv files...');
	    
	    $csvImporter->import();
	    
	    foreach ($importData as $data) {
	        $this->info('Imported data from ' . $data['file_path'] . '.');
	    }
	}
}
