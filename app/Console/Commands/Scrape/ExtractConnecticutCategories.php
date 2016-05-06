<?php

namespace App\Console\Commands\Scrape;

use Goutte\Client;
use Illuminate\Console\Command;
use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\Scrapers\Connecticut\Extractors\CategoriesExtractor;

/**
 * Command class that handles the refreshing of records in Files table.
 */
class ExtractConnecticutCategories extends Command 
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'scrape_extract:connecticut_categories';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Extract connecticut categories';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(Client $client, ScrapeFilesystemInterface $filesystem) {
		$extractor = CategoriesExtractor::create($client, $filesystem);
        $extractor->extractCategories();
	}
}
