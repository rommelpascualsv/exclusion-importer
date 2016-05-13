<?php

namespace App\Http\Controllers;

use App\Import\Scrape\Scrapers\Connecticut\DownloadOptionsPage;
use App\Import\Scrape\Scrapers\Connecticut\MainPage;
use Goutte\Client;
use Illuminate\Database\DatabaseManager;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Database\ConnectionInterface;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;
use Illuminate\Support\Facades\DB;

class ScrapeController extends BaseController
{
	public function test(OptionCollection $options)
	{
//        $categories = [];
//        
//        foreach($options as $option) {
//            $categoryId = DB::table('ct_license_categories')->where('key', $option->getCategory()->getDir())->value('id');
//            echo '["key"=>\'',$option->getCategory()->getDir(),'.', $option->getFileName(), '\', "name" =>"', $option->getName(),'", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => ',$categoryId,'],<br>';
//            $categories[$option->getCategory()->getDir()] = $option->getCategory();
//        }
        
//        $counter = 1;
//        foreach($categories as $category) {
//            echo '["id" => '.$counter.', "key"=>"', $category->getDir(), '", "name" =>"', $category->getName(),'", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],<br>';
//            $counter++;
//        }
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