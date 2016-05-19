<?php

namespace App\Console\Commands\Scrape\Connecticut;

use App\Import\Scrape\Scrapers\Connecticut\CsvDownloader;
use Illuminate\Console\Command;
use App\Exceptions\Scrape\ScrapeException;
use App\Exceptions\Scrape\Connecticut\DownloadOptionMissingException;
use App\Import\Scrape\Scrapers\Connecticut\Data\Option;
use App\Import\Scrape\ProgressTrackers\CliProgressTracker;

/**
 * Command class that handles the refreshing of records in Files table.
 */
class DownloadCsv extends Command 
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'scrape_connecticut:download_csv';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Download csv files from Connecticut site';
	
	/**
	 * Execute the console command.
	 *
	 * @param CsvDownloader $downloader
	 * @return mixed
	 */
	public function handle(CsvDownloader $downloader)
	{	
	    $downloader->attachProgressTracker(new CliProgressTracker($this))
            ->download();
	}
}
