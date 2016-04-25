<?php

namespace App\Console\Commands\Scrape\Connecticut;

use Illuminate\Console\Command;
use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\Scrapers\Connecticut\Extractors\CsvHeadersExtractor;
use App\Import\Scrape\Scrapers\Connecticut\Extractors\HeadersOrganizer;

/**
 * Command class that handles the refreshing of records in Files table.
 */
class OrganizeCsvHeaders extends Command 
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'scrape_connecticut:organize_csv_headers';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Organize extracted csv headers from all downloaded csv files for data analysis';
	
	/**
	 * Execute the console command.
	 * @param ScrapeFilesystemInterface $filesystem
	 */
	public function handle(ScrapeFilesystemInterface $filesystem)
	{
		$this->line('Organizing headers from all csv files in ' . $filesystem->getPath('csv/connecticut') . ' ...');
		
		$extractor = CsvHeadersExtractor::create($filesystem);
		$headersData = $extractor->extract()->getData();
		$organizer = new HeadersOrganizer(
				$headersData,
				$filesystem->getPath('extracted/connecticut/organized-headers.csv')
		);
		
		$organizer->organize()->save();
		
		$this->info('Organized headers in ' . count($organizer->getData()) . ' csv files.');
		$this->info('Result saved in ' . $organizer->getSavePath());
	}
}
