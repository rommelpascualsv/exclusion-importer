<?php

namespace App\Http\Controllers;

use App\Import\Scrape\Crawlers\ConnecticutCrawler;
use Laravel\Lumen\Routing\Controller as BaseController;

class ScrapeController extends BaseController
{
	public function test()
	{
		ini_set('max_execution_time', 99999);
		
		$crawler = ConnecticutCrawler::create(
			storage_path('app'),
			'Certified_Public_Accountant_Certificate.csv'
		);
		
		$crawler->downloadFile();
	}
}