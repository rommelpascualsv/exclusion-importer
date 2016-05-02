<?php

namespace App\Http\Controllers;

use App\Import\Scrape\Scrapers\Connecticut\DownloadOptionsPage;
use App\Import\Scrape\Scrapers\Connecticut\MainPage;
use Goutte\Client;
use Illuminate\Database\DatabaseManager;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Database\ConnectionInterface;

class ScrapeController extends BaseController
{
	public function test()
	{
		
		/* dd($a); */
		
		/* $a->download(); */
		
		/* $mainPage = MainPage::scrape($client);
		$downloadOptionsPage = DownloadOptionsPage::scrape($mainPage, [
				'ctl00$MainContentPlaceHolder$ckbRoster0',
				'ctl00$MainContentPlaceHolder$ckbRoster4',
				'ctl00$MainContentPlaceHolder$ckbRoster27'
		]);
		
		echo $downloadOptionsPage->getCrawler()->html(); */
	}
}