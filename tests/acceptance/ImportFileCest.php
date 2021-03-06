<?php

class ImportFileCest
{
	public function _before(AcceptanceTester $I)
    {
    	$I->amOnPage($this->getPath());
    	
    	$I->see("Update Exclusion Lists");
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
    	
    	$I->seeImportUrlInDatabase($this->getPrefix(), $this->getInvalidUrl());
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
    	
    	$I->seeImportUrlInDatabase($this->getPrefix(), $this->getValidUrl());
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
    	return $this->getPath() . "/" . $this->getPrefix();
    }
}
