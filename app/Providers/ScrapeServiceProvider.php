<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Import\Scrape\Components\FilesystemInterface;
use League\Flysystem\Adapter\Local;
use Laravel\Lumen\Application;
use App\Import\Scrape\Components\Filesystem;
use App\Import\Scrape\Components\TestFilesystem;
use League\Flysystem\AdapterInterface;
use App\Import\Scrape\Crawlers\ConnecticutCrawler;
use Goutte\Client;
use App\Import\Scrape\Scrapers\Connecticut\Data\CategoryCollection;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;

class ScrapeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    	$this->registerGoutteClient();
    	$this->registerConnecticut();
    }
    
    /** 
     * {@inheritDoc}
     * @see \Illuminate\Support\ServiceProvider::provides()
     */
    public function provides()
    {
    	return [
    			Client::class,
    			ConnecticutCrawler::class,
    			CategoryCollection::class,
    			OptionCollection::class
    	];
    }
    
    /**
     * Register Goutte Client
     */
    protected function registerGoutteClient()
    {
    	$this->app->bind(Client::class, function() {
    		return new Client([
    				'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36'
    		]);
    	});
    }
    
    /**
     * Register Crawlers
     */
    protected function registerConnecticut()
    {
    	$this->app->bind(ConnecticutCrawler::class, function() {
    		return new ConnecticutCrawler(
    				$this->app->make(Client::class),
    				'',
    				[]
    		);
    	});
    	
    	/* bind category collection */
    	$this->app->singleton(CategoryCollection::class, function() {
    		$jsonPath = config('scrape.import.connecticut_categories');
    		$data = json_decode(file_get_contents($jsonPath), true);
    		
    		return new CategoryCollection($data);
    	});
    	
    	$this->app->singleton(OptionCollection::class, function() {
    		/** @var CategoryCollection $categoryCollection */
    		$categoryCollection = $this->app->make(CategoryCollection::class);
    		
    		return $categoryCollection->getOptions(config('scrape.connecticut_categories'));
    	});
    }
}
