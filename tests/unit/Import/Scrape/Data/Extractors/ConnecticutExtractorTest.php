<?php
namespace Import\Scrape\Data\Extractors;


use App\Import\Scrape\Crawlers\ConnecticutCrawler;
use App\Import\Scrape\Data\Extractors\ConnecticutExtractor;
use App\Import\Scrape\Components\FilesystemInterface;
use App\Import\Scrape\Components\TestFilesystem;

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
    	$this->filesystem = $this->getModule('Lumen')->app->make(TestFilesystem::class);
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
				    					'file_name' => 'Certified_Public_Accountant_Certificate'
				    			],
				    			'certified_public_accountant_firm_permit' => [
				    					'name' => 'Certified Public Accountant Firm Permit',
				    					'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster1',
				    					'file_name' => 'Certified_Public_Accountant_Firm_Permit'
				    			],
				    			'certified_public_accountant_license' => [
				    					'name' => 'Certified Public Accountant License',
				    					'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster2',
				    					'file_name' => 'Certified_Public_Accountant_License'
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