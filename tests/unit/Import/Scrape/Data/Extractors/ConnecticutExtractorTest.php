<?php
namespace Import\Scrape\Data\Extractors;


use App\Import\Scrape\Crawlers\ConnecticutCrawler;
use App\Import\Scrape\Data\Extractors\ConnecticutExtractor;

class ConnecticutExtractorTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
	
    /**
     * @var ConnecticutExtractor
     */
    protected $extractor;
    
    protected function _before()
    {
    	$this->filesystem = app('scrape_test_filesystem');
    	$this->extractor = new ConnecticutExtractor(
    			ConnecticutCrawler::create('', []),
    			$this->filesystem
    	);
    }

    protected function _after()
    {
    }
    
    public function testGetCategoriesData()
    {
    	$categoryHeadersCrawler = $this->getCategoryHeaderCrawlers();
    	
    	$categories = $this->extractor->getCategoriesData($categoryHeadersCrawler);
    	
    	$this->assertCount(36, $categories);
    	$this->assertArrayHasKey('accountancy', $categories);
    	$this->assertArrayHasKey('standards_home_heating_fuel_dealer_gas_stations_and_weighing_devices', $categories);
    	$this->assertArrayHasKey('youth_camp_licensing', $categories);
    }
    
    public function testGetCategoriesDataSavesFirstDuplicate()
    {
    	$categoryHeadersCrawler = $this->getCategoryHeaderCrawlers();
    	 
    	$categories = $this->extractor->getCategoriesData($categoryHeadersCrawler);
    	$optionFieldName = $categories['healthcare_practitioners']['options']['dietitian_nutritionist']['field_name'];
    	
    	$this->assertEquals('ctl00$MainContentPlaceHolder$ckbRoster109', $optionFieldName);
    }
    
    public function testGetCategoryData()
    {
    	$categoryHeaderCrawler = $this->getFirstCategoryHeaderCrawler();
    	
    	$actual = $this->extractor->getCategoryData($categoryHeaderCrawler);
    	$expected = [
    					'name' => 'Accountancy',
    					'options' => [
				    			'certified_public_accountant_certificate' => [
				    					'name' => 'Certified Public Accountant Certificate',
				    					'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster0',
				    					'file_name' => 'certified_public_accountant_certificate'
				    			],
				    			'certified_public_accountant_firm_permit' => [
				    					'name' => 'Certified Public Accountant Firm Permit',
				    					'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster1',
				    					'file_name' => 'certified_public_accountant_firm_permit'
				    			],
				    			'certified_public_accountant_license' => [
				    					'name' => 'Certified Public Accountant License',
				    					'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster2',
				    					'file_name' => 'certified_public_accountant_license'
				    			]
				    	]
    			];
    	
    	$this->assertEquals($expected, $actual);
    }
    
    public function testExtractCategories()
    {
    	$this->extractor->extractCategories();
    
    	$this->assertFileExists(codecept_data_dir('scrape/extracted/connecticut-categories.json'));
    }
    
    protected function getCategoryHeaderCrawlers()
    {
    	$mainCrawler = $this->extractor->getMainCrawler();
    	
    	return $this->extractor->getCategoryHeadersCrawler($mainCrawler);
    }
    
    protected function getFirstCategoryHeaderCrawler()
    {
    	$mainCrawler = $this->extractor->getMainCrawler();
    	$categoryHeadersCrawler = $this->extractor->getCategoryHeadersCrawler($mainCrawler);
    	
    	return $categoryHeadersCrawler->eq(0);
    }
}