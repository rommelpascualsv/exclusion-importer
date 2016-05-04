<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\Adapter\Local;
use Laravel\Lumen\Application;
use League\Flysystem\AdapterInterface;
use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\Components\ScrapeFilesystem;

class FilesystemServiceProvider extends ServiceProvider
{
	/**
	 * @var boolean
	 */
	protected $defer = true;
	
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    	$this->registerScrapeFilesystem();
    }
    
    /** 
     * {@inheritDoc}
     * @see \Illuminate\Support\ServiceProvider::provides()
     */
    public function provides()
    {
    	return [
    			ScrapeFilesystemInterface::class,
    			'scrape_test_filesystem'
    	];
    }
    
    /**
     * Register scrape filesystem
     */
    protected function registerScrapeFilesystem()
    {	
    	$this->app->singleton(ScrapeFilesystemInterface::class, function() {    		
    		return $this->getScrapeFilesystem(ScrapeFilesystemInterface::PATH);
    	});
    	
    	$this->app->bind('scrape_test_filesystem', function() {
    		return $this->getScrapeFilesystem(ScrapeFilesystemInterface::TEST_PATH);
    	});
    }
    
    /**
     * Get scrape filesystem
     * @param unknown $path
     * @return \App\Import\Scrape\Components\ScrapeFilesystem
     */
    protected function getScrapeFilesystem($path)
    {
    	$path = $this->app->basePath($path);
    	
    	return new ScrapeFilesystem(
    			new Local(
		    		$path,
		    		LOCK_EX,
		    		Local::DISALLOW_LINKS,
		    		[
		    				'file' => [
		    						'public' => 0775,
		    						'private' => 0770,
		    				],
		    				'dir' => [
		    						'public' => 0775,
		    						'private' => 0770,
		    				]
		    		]
    			),
    			['visibility' => AdapterInterface::VISIBILITY_PRIVATE]
    	);
    }
    
    /**
     * Get local adapter
     * @param string $path
     * @return Local
     */
    protected function getLocalAdapter($path)
    {
    	return new Local(
    		$path,
    		LOCK_EX,
    		Local::DISALLOW_LINKS,
    		[
    				'file' => [
    						'public' => 0775,
    						'private' => 0770,
    				],
    				'dir' => [
    						'public' => 0775,
    						'private' => 0770,
    				]
    		]
    	);
    }
    
    /**
     * Get config
     * @return array
     */
    protected function getConfig()
    {
    	return ['visibility' => AdapterInterface::VISIBILITY_PRIVATE];
    }
}
