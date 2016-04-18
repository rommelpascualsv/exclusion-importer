<?php

namespace App\Console\Commands\Scrape;

use App\Import\Scrape\Scrapers\Connecticut\CsvDownloader;
use Illuminate\Console\Command;

/**
 * Command class that handles the refreshing of records in Files table.
 */
class ScrapeConnecticut extends Command 
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'scrape:connecticut';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Download csv files from Connecticut site';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(CsvDownloader $downloader) {
		$downloader->download();
	}
}
