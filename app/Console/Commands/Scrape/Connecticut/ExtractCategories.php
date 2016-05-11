<?php

namespace App\Console\Commands\Scrape\Connecticut;

use Goutte\Client;
use Illuminate\Console\Command;
use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\Scrapers\Connecticut\Extractors\CategoriesExtractor;
use App\Import\Scrape\Scrapers\Connecticut\MainPage;

/**
 * Command class that handles the refreshing of records in Files table.
 */
class ExtractCategories extends Command 
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'scrape_connecticut:extract_categories';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Extract connecticut categories to a json file';
	
	/**
	 * Execute the console command
	 * @param Client $client
	 * @param ScrapeFilesystemInterface $filesystem
	 */
	public function handle(Client $client, ScrapeFilesystemInterface $filesystem) {
	    $this->line('Crawling the main page...');
	    
	    $mainPage = MainPage::scrape($client);
	    
	    $this->info('Main page crawled.');
	    $this->line('Extracting the category data from the main page...');
	    
		$extractor = new CategoriesExtractor($mainPage, $filesystem);
        $extractor->extract()->save();
        
        $this->info('Connecticut category data saved in ' . $extractor->getSaveFilePath());
	}
}
