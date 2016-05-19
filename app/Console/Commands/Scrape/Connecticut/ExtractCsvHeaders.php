<?php

namespace App\Console\Commands\Scrape\Connecticut;

use Illuminate\Console\Command;
use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\Scrapers\Connecticut\Extractors\CsvHeadersExtractor;

/**
 * Command class that handles the refreshing of records in Files table.
 */
class ExtractCsvHeaders extends Command 
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'scrape_connecticut:extract_csv_headers';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Extract csv headers from all downloaded csv files for data analysis';
	
	/**
	 * Execute the console command.
     * 
	 * @param ScrapeFilesystemInterface $filesystem
	 */
	public function handle(ScrapeFilesystemInterface $filesystem)
	{
        $extractor = CsvHeadersExtractor::create($filesystem);
        $extractor->attachProgressTracker(new \App\Import\Scrape\ProgressTrackers\CliProgressTracker($this))
            ->extract()
            ->save();
	}
}
