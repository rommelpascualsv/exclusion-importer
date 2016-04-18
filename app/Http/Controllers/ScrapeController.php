<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Import\Scrape\Scrapers\Connecticut\MainPage;
use App\Import\Scrape\Scrapers\Connecticut\DownloadOptionsPage;
use App\Import\Scrape\Scrapers\Connecticut\Data\CategoryCollection;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;
use App\Import\Scrape\Scrapers\Connecticut\CsvDownloader;

class ScrapeController extends BaseController
{
	public function test(CsvDownloader $a)
	{
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