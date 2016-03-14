<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{	
	public function seeImportUrlInDatabse($prefix, $expectedUrl)
	{
		$db = $this->getModule('Db');
		$actualUrl = $db->grabFromDatabase('exclusion_lists', 'import_url', array('prefix' => $prefix));
		
		$this->assertEquals($expectedUrl, $actualUrl);
	}
	
	public function seeTableHasRecords($table)
	{
		$db = $this->getModule('Db');
		// by not passing a second parameter, this call will check if table is empty or not.
		$db->seeInDatabase($table);
	}
	
	public function seeTableHasNoRecords($table)
	{
		$db = $this->getModule('Db');
		// by not passing a second parameter, this call will check if table is empty or not.
		$db->dontSeeInDatabase($table);
	}
	
	public function truncateTable($table)
	{
		$db = $this->getModule('Db');
		$db->driver->load(["TRUNCATE TABLE $table;"]);
	}
}
