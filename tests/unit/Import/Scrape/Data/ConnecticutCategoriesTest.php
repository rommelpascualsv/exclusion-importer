<?php
namespace Import\Scrape\Data;


use App\Import\Scrape\Data\ConnecticutCategories;

class ConnecticutCategoriesTest extends \Codeception\TestCase\Test
{	
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testGetOption()
    {
		$option = ConnecticutCategories::getOption(
				ConnecticutCategories::OPT_AMBULATORY_SURGICAL_CENTER
		);
		
		$this->assertEquals(
				[
					'label' => 'Ambulatory Surgical Center',
					'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster25'
				],
				$option
		);
    }
    
    public function testGetOptions()
    {
    	/* using option constants */
    	$options = ConnecticutCategories::getOptions([
    			ConnecticutCategories::OPT_ACCOUNTANT_CERTIFICATE,
    			ConnecticutCategories::OPT_ANIMAL_IMPORTERS,
    			ConnecticutCategories::OPT_AMBULATORY_SURGICAL_CENTER
    	]);
    			 
    	$expected = [
    			ConnecticutCategories::getOption(ConnecticutCategories::OPT_ACCOUNTANT_CERTIFICATE),
    			ConnecticutCategories::getOption(ConnecticutCategories::OPT_ANIMAL_IMPORTERS),
    			ConnecticutCategories::getOption(ConnecticutCategories::OPT_AMBULATORY_SURGICAL_CENTER)
    	];
    			 
    	$this->assertEquals($expected, $options);
    	
    	/* with category constants */
    	$options = ConnecticutCategories::getOptions([
    			ConnecticutCategories::CAT_ACCOUNTANCY,
    			ConnecticutCategories::OPT_ANIMAL_IMPORTERS,
    			ConnecticutCategories::CAT_AMBULATORY_SURGICAL_RECOVERY_CARE_CENTERS
    	]);
    	
    	$expected = [
    			ConnecticutCategories::getOption(ConnecticutCategories::OPT_ACCOUNTANT_CERTIFICATE),
    			ConnecticutCategories::getOption(ConnecticutCategories::OPT_ACCOUNTANT_FIRM_PERMIT),
    			ConnecticutCategories::getOption(ConnecticutCategories::OPT_ACCOUNTANT_LICENSE),
    			ConnecticutCategories::getOption(ConnecticutCategories::OPT_ANIMAL_IMPORTERS),
    			ConnecticutCategories::getOption(ConnecticutCategories::OPT_AMBULATORY_SURGICAL_CENTER)
    	];
    }
}