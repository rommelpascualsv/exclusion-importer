<?php
namespace Import\Scrape\Scrapers\Connecticut\Data;


use App\Import\Scrape\Scrapers\Connecticut\Data\CategoryCollection;
use App\Import\Scrape\Scrapers\Connecticut\Data\Option;

class CategoryCollectionTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
	
    /**
     * @var CategoryCollection
     */
    protected $collection;
    
    protected function _before()
    {
    	$this->collection = new CategoryCollection([
    			'accountancy' => [
	    				'name' => 'Accountancy',
	    				'options' => [
	    						'certified_public_accountant_certificate' => [
	    								'name' => 'Certified Public Accountant Certificate',
	    								'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster0',
	    								'file_name' => 'Certified_Public_Accountant_Certificate',
	    						],
	    						'certified_public_accountant_firm_permit'=> [
	    								'name'=> 'Certified Public Accountant Firm Permit',
	    								'field_name'=> 'ctl00$MainContentPlaceHolder$ckbRoster1',
	    								'file_name'=> 'Certified_Public_Accountant_Firm_Permit'
	    						],
	    						'certified_public_accountant_license'=> [
	    								'name'=> 'Certified Public Accountant License',
	    								'field_name'=> 'ctl00$MainContentPlaceHolder$ckbRoster2',
	    								'file_name'=> 'Certified_Public_Accountant_License'
	    						]
	    				]
    			],
    			'agriculture' => [
    					'name' => 'Agriculture',
    					'options' => [
    							'animal_importers' => [
    									'name' => 'Animal Importers',
    									'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster3',
    									'file_name' => 'Animal_Importers'
    							],
    							'bulk_milk_tankers' => [
    									'name' => 'Bulk Milk Tankers',
    									'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster4',
    									'file_name' => 'Bulk_Milk_Tankers'
    							]
    					],
    					
    			],
    			'ambulatory_surgical_recovery_care_centers' => [
    					'name' => 'Ambulatory Surgical Centers/Recovery Care Centers',
    					'options' => [
    							'ambulatory_surgical_center' => [
    									'name' => 'Ambulatory Surgical Center',
    									'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster25',
    									'file_name' => 'Ambulatory_Surgical_Center'
    							]
    					]
    			]
    			
    	]);
    }

    protected function _after()
    {
    }

    // tests
    public function testGetOptions()
    {
    	$options = $this->collection->getOptions([
    			'agriculture',
    			'accountancy' => [
    					'certified_public_accountant_certificate',
    					'certified_public_accountant_license'
    			]
    	]);
    	
		$this->assertCount(4, $options);
		$this->assertInstanceOf(Option::class, $options[0]);
		$this->assertEquals('Animal Importers', $options[0]->getName());
		$this->assertEquals('Bulk Milk Tankers', $options[1]->getName());
		$this->assertEquals('Certified Public Accountant Certificate', $options[2]->getName());
		$this->assertEquals('Certified Public Accountant License', $options[3]->getName());
    }
}