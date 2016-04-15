<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Import\Scrape\Components\FilesystemInterface;
use League\Flysystem\Adapter\Local;
use Laravel\Lumen\Application;
use App\Import\Scrape\Components\Filesystem;
use App\Import\Scrape\Components\TestFilesystem;
use League\Flysystem\AdapterInterface;

class FilesystemServiceProvider extends ServiceProvider
{
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
    			FilesystemInterface::class,
    			TestFilesystem::class
    	];
    }
    
    /**
     * Register scrape filesystem
     */
    protected function registerScrapeFilesystem()
    {
    	$this->app->singleton(FilesystemInterface::class, function(Application $app) {
    		$path = $app->basePath(Filesystem::getFolderPath());
    	
    		return new Filesystem(
    				$this->getLocalAdapter($path),
    				$this->getConfig()
    		);
    	});
    	
    		$this->app->singleton(TestFilesystem::class, function(Application $app) {
    			$path = $app->basePath(TestFilesystem::getFolderPath());
    		
    			return new TestFilesystem(
    					$this->getLocalAdapter($path),
    					$this->getConfig()
    					);
    		});
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
