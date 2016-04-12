<?php

namespace App\Http\Controllers;

use App\Import\Scrape\Crawlers\ConnecticutCrawler;
use Laravel\Lumen\Routing\Controller as BaseController;

class ScrapeController extends BaseController
{
	public function test()
	{
		$crawler = ConnecticutCrawler::create();
		$crawler->downloadFile();
	}
}