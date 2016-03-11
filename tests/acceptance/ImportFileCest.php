<?php

class ImportFileCest
{
    public function _before(AcceptanceTester $I)
    {
    	$I->amOnPage("/import");
    	
    	$I->see("Update Exclusion Lists");
    	
    	curl_setopt(curl_init(), CURLOPT_TIMEOUT, 0);
    }

    public function _after(AcceptanceTester $I)
    {
    	$I->truncateTable('nyomig_records');
    }

    // tests
    public function ImportFileUsingAnInvalidUrl(AcceptanceTester $I)
    {
    	$I->truncateTable('nyomig_records');
    	 
    	$I->wantTo('Import file');
    
    	$I->sendGet('http://app.exclusions-import.dev:8088/import/nyomig', [
    			'url' => 'http://www.yahoo.com'
    	]);
    
    	$I->seeTableHasNoRecords("nyomig_records");
    }
    
    public function ImportFileUsingNewUrl(AcceptanceTester $I)
    {
    	$I->truncateTable('nyomig_records');
    	 
    	$I->wantTo('Import file');
    	 
    	$I->sendGet('http://app.exclusions-import.dev:8088/import/nyomig', [
    			'url' => 'http://www.omig.ny.gov/data/gensplistns.php'
    	]);
    	 
    	$I->seeTableHasRecords("nyomig_records");
    }
    
    public function ImportFileUsingDefaultUrl(AcceptanceTester $I)
    {
    	$I->truncateTable('nyomig_records');
    	
    	$I->wantTo('Import file');

		$I->sendGet('http://app.exclusions-import.dev:8088/import/nyomig', [
    			'url' => ""
    	]);
		
		$I->seeTableHasRecords("nyomig_records");
    }
       
}
