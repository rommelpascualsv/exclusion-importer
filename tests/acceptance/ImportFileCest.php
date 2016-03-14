<?php

class ImportFileCest
{
	public function _before(AcceptanceTester $I)
    {
    	$I->amOnPage($this->getPath());
    	
    	$I->see("Update Exclusion Lists");
    	
    	curl_setopt(curl_init(), CURLOPT_TIMEOUT, 600);
    }

    public function _after(AcceptanceTester $I)
    {
    	$I->truncateTable($this->getRecordsTableName());
    }

    // tests
    public function ImportFileUsingAnInvalidUrl(AcceptanceTester $I)
    {
    	$I->truncateTable($this->getRecordsTableName());
    	 
    	$I->wantTo('Import file');
    	
    	$I->fillField("text_nyomig", $this->getInvalidUrl());
    	 
    	$url = $I->grabTextFrom("input.text_nyomig");
    
    	$I->sendGet($this->getRestPath(), [
    			'url' => $url
    	]);
    
    	$I->seeTableHasNoRecords($this->getRecordsTableName());
    	
    	$I->seeImportUrlInDatabse($this->getPrefix(), $this->getInvalidUrl());
    }
    
    public function ImportFileUsingNewUrl(AcceptanceTester $I)
    {
    	$I->truncateTable($this->getRecordsTableName());
    	 
    	$I->wantTo('Import file');
    	$I->fillField("text_nyomig", $this->getValidUrl());
    	
    	$url = $I->grabTextFrom("input.text_nyomig");
    	
    	$I->sendGet($this->getRestPath(), [
    			'url' => $url
    	]);
    	
    	$I->seeTableHasRecords($this->getRecordsTableName());
    	
    	$I->seeImportUrlInDatabse($this->getPrefix(), $this->getValidUrl());
    }
    
    public function ImportFileUsingDefaultUrl(AcceptanceTester $I)
    {
    	$I->truncateTable($this->getRecordsTableName());
    	
    	$I->wantTo('Import file');

		$I->sendGet($this->getRestPath(), [
    			'url' => ""
    	]);
		
		$I->seeTableHasRecords($this->getRecordsTableName());
    }
    
    private function getPath()
    {
    	return "/import";	
    }
    
    private function getRecordsTableName()
    {
    	return "nyomig_records";
    }
    
    private function getValidUrl()
    {
    	return "http://www.omig.ny.gov/data/gensplistns.php";
    }
    private function getInvalidUrl()
    {
    	return "www.yahoo.com";
    }
    
    private function getPrefix()
    {
    	return "nyomig";
    }
    
    private function getRestPath()
    {
    	return self::getPath()."/".self::getPrefix();
    }
}
