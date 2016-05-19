<?php

namespace App\Console\Commands\Scrape\Connecticut;

use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\ProgressTrackers\CliProgressTracker;
use App\Import\Scrape\Scrapers\Connecticut\Extractors\CsvHeadersExtractor;
use App\Import\Scrape\Scrapers\Connecticut\Extractors\HeadersOrganizer;
use Illuminate\Console\Command;

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
        $progressTracker = new CliProgressTracker($this);
        
        $extractor = CsvHeadersExtractor::create($filesystem);
		$headersData = $extractor
            ->attachProgressTracker($progressTracker)
            ->extract()
            ->getData();
		
        $organizer = new HeadersOrganizer(
            $headersData,
            $filesystem->getPath('extracted/connecticut/organized-headers.csv')
		);
		
		$organizer->attachProgressTracker($progressTracker)
            ->organize()
            ->save();
	}
}
