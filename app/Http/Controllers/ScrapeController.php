<?php

namespace App\Http\Controllers;

use App\Import\Scrape\Crawlers\ConnecticutCrawler;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Import\Scrape\Data\ConnecticutCategories;

class ScrapeController extends BaseController
{
	public function test()
	{
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
		
		$crawler->downloadFiles();
	}
}