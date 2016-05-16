<?php

namespace App\Console\Commands\Scrape\Connecticut;

use App\Import\Scrape\Scrapers\Connecticut\CsvDownloader;
use Illuminate\Console\Command;
use App\Exceptions\Scrape\ScrapeException;
use App\Exceptions\Scrape\Connecticut\DownloadOptionMissingException;
use App\Import\Scrape\Scrapers\Connecticut\Data\Option;

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
		try {
			$this->line('Crawling the Main Page...');
			
			$mainPage = $downloader->scrapeMainPage();
			$this->info('Success (URL: ' . $mainPage->getRequestUri() . ')');
			
			$this->line('Submitting form to crawl the Download Options Page...');
			
			$downloadOptionsPage = $downloader->scrapeDownloadOptionsPage($mainPage);
			$this->info('Success (URL: ' . $downloadOptionsPage->getRequestUri() . ')');
			
			$this->line('Extracting roster IDs...');
			
			$options = $downloader->getOptions();
			$existingOptions = [];
			$rosterIds = [];
			
			/** @var Option $option */
			foreach ($options as $option) {
				try {
					$rosterId = $downloadOptionsPage->getRosterId($option);
				} catch (DownloadOptionMissingException $e) {
					$this->error($e->getMessage() . '. Proceeding to next option.');
					
					continue;
				}
				
				$existingOptions[] = $option;
				$rosterIds[] = $rosterId;
			
				$this->info('Got roster ID for ' . $option->getDescriptiveName());
			}
			
			$this->line('Downloading files to ' . $downloader->getDownloadPath() . '...');
			
			foreach ($existingOptions as $i => $option) {
				$fileName = $downloader->getFileName($option);
					
				$csvPage = $downloader->downloadFile([
						'roster_id' => $rosterIds[$i],
						'file_name' => $fileName
				], $downloadOptionsPage);
					
				$this->info('Downloaded ' . $fileName . ' from ' . $csvPage->getRequestUri());
			}
		} catch (ScrapeException $e) {
			$this->error($e->getMessage());
		}
	}
}
