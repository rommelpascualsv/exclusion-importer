<?php

namespace App\Console\Commands\Scrape\Connecticut;

use App\Import\Scrape\ProgressTrackers\CliProgressTracker;
use App\Import\Scrape\Scrapers\Connecticut\Extractors\CategoriesExtractor;
use Goutte\Client;
use Illuminate\Console\Command;

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
     * 
	 * @param CategoriesExtractor $extractor
     * @param Client $client
	 */
	public function handle(CategoriesExtractor $extractor, Client $client)
    {
        $extractor
            ->attachProgressTracker(new CliProgressTracker($this))
            ->extract($client)
            ->save();
	}
}
