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
}
