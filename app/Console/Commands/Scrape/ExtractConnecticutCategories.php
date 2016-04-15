<?php

namespace App\Console\Commands\Scrape;

use Illuminate\Console\Command;
use App\Import\Scrape\Data\Extractors\ConnecticutExtractor;

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
	public function handle(ConnecticutExtractor $extractor) {
		$extractor->extractCategories();
	}
}
