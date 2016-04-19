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
	 * @param CsvDownloader $downloader
	 * @return mixed
	 */
	public function handle(CsvDownloader $downloader)
	{
		$this->line('Crawling the Main Page...');
		
		$mainPage = $downloader->scrapeMainPage();
		$this->info('Success (URL: ' . $mainPage->getRequestUri() . ')');
		
		$this->line('Submitting form to crawl the Download Options Page...');
		
		$downloadOptionsPage = $downloader->scrapeDownloadOptionsPage($mainPage);
		$this->info('Success (URL: ' . $downloadOptionsPage->getRequestUri() . ')');
		
		$this->line('Extracting roster IDs...');
		
		$options = $downloader->getOptions();
		$rosterIds = [];
		
		foreach ($options as $option) {
			$optionName = $option->getName();
			$rosterId = $downloader->getRosterId($optionName, $downloadOptionsPage);
				
			$rosterIds[] = $rosterId;
				
			$this->info('Got roster ID for ' . $optionName);
		}
		
		$this->line('Downloading files to ' . $downloader->getDownloadPath() . '...');
		
		foreach ($options as $i => $option) {
			$fileName = $downloader->getFileName($option);
			
			$csvPage = $downloader->downloadFile([
					'roster_id' => $rosterIds[$i],
					'file_name' => $fileName
			], $downloadOptionsPage);
			
		
			$this->info('Downloaded ' . $fileName . ' from ' . $csvPage->getRequestUri());
		}
	}
}
