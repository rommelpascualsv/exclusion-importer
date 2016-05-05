<?php

namespace App\Console\Commands\Scrape\Connecticut;

use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\Scrapers\Connecticut\CsvImporter;
use Illuminate\Console\Command;

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
	    $importData = $this->getImportData($filesystem);
	    $csvImporter = new CsvImporter($importData, app('db')->connection());
	    
	    $this->line('Importing data from ' . count($importData) . ' csv files...');
	    
	    $csvImporter->import();
	    
	    foreach ($importData as $data) {
	        $this->info('Imported data from ' . $data['file_path'] . '.');
	    }
	}
	
	/**
	 * Get import data
	 * @param ScrapeFilesystemInterface $filesystem
	 */
	protected function getImportData(ScrapeFilesystemInterface $filesystem)
	{
	    $files = [
	        'ambulatory_surgical_centers_recovery_care_centers' => [
	            'ambulatory_surgical_center'
	        ],
	        'controlled_substances_practitioners_labs_manufacturers' => [
	            'controlled_substance_laboratories',
	            'manufacturers_of_drugs_cosmetics_and_medical_devices'
	        ],
	        'healthcare_practitioners' => [
	            'acupuncturist'
	        ]
	    ];
	    $importData = [];
	    
	    foreach ($files as $category => $options) {
	        foreach ($options as $option) {
	            $importData[] = [
	                'category' => $category,
	                'option' => $option,
	                'file_path' => $filesystem->getPath('csv/connecticut/' . $category . '/' . $option . '.csv')
	            ];
	        }
	    }
	    
	    return $importData;
	}
}
