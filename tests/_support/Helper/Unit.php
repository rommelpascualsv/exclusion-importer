<?php
namespace Helper;

use Laravel\Lumen\Application;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Unit extends \Codeception\Module
{
	/**
	 * Get lumen app
	 * @return Application
	 */
	public function getLumenApp()
	{
		return $this->getModule('Lumen')->app;
	}
	
	/**
	 * Assert scrape file exist
	 * @param string $file
	 */
	public function assertScrapeFileExist($file)
	{
		$this->assertFileExists(codecept_data_dir('scrape' . DIRECTORY_SEPARATOR .  $file));
	}
}
