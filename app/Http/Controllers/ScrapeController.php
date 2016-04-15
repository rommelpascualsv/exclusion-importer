<?php

namespace App\Http\Controllers;

use App\Import\Scrape\Components\FilesystemInterface;
use App\Import\Scrape\Crawlers\ConnecticutCrawler;
use App\Import\Scrape\Data\ConnecticutCategories;
use App\Import\Scrape\Data\Extractors\ConnecticutExtractor;
use Laravel\Lumen\Routing\Controller as BaseController;

class ScrapeController extends BaseController
{
	public function test(FilesystemInterface $filesystem)
	{
		dd($filesystem);
		ini_set('max_execution_time', 99999);
		
		$crawler = ConnecticutCrawler::create(
			storage_path('app'),
			[
					ConnecticutCategories::OPT_ACCOUNTANT_CERTIFICATE,
					ConnecticutCategories::OPT_ACCOUNTANT_FIRM_PERMIT,
					ConnecticutCategories::OPT_ANIMAL_IMPORTERS,
					ConnecticutCategories::OPT_AMBULATORY_SURGICAL_CENTER
			]
		);
		
		$crawler = ConnecticutCrawler::create(storage_path('app'), []);
		
		echo "\xC2";
		exit;
		dd($crawler);		
		$extractor = new ConnecticutExtractor($crawler);
		$extractor->saveCategories();
		
		
		
		/* $crawler->downloadFiles(); */
	}
}