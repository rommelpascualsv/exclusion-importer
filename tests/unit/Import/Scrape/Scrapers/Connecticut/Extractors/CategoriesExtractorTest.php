<?php
namespace Import\Scrape\Scrapers\Connecticut\Extractors;
	

use App\Import\Scrape\Scrapers\Connecticut\Extractors\CategoriesExtractor;
use App\Import\Scrape\Scrapers\Connecticut\MainPage;
use Goutte\Client;
use GuzzleHttp\json_decode;

class CategoriesExtractorTest extends \Codeception\TestCase\Test
{	
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var MainPage
     */
    protected $mainPage;
    
    /**
     * @var CategoriesExtractor
     */
    protected $extractor;
    
    protected function _before()
    {
        $this->client = app(Client::class);
        $this->mainPage = MainPage::scrape($this->client);
        $this->filesystem = app('scrape_test_filesystem');
        $this->extractor = new CategoriesExtractor($this->mainPage, $this->filesystem);
    }

    protected function _after()
    {
    }

    // tests
    public function testExtractCategoryData()
    {
        $headingNode = $this->mainPage->getPanelNodes()->eq(0);
        $i = 0;
        
        $categoryData = $this->extractor->extractCategoryData($headingNode, $i);
        $expectedCategoryData = [
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
        
        $this->assertSame($expectedCategoryData, $categoryData);
    }
    
    public function testExtract()
    {
        $this->extractor->extract();
        
        $categories = $this->extractor->getData();
        
        $this->assertCount(36, $categories);
        $this->assertArrayHasKey('accountancy', $categories);
        $this->assertArrayHasKey('standards_home_heating_fuel_dealer_gas_stations_and_weighing_devices', $categories);
        $this->assertArrayHasKey('youth_camp_licensing', $categories);
    }
    
    public function testExtractGetFirstOptionOnDuplicate()
    {
        $this->extractor->extract();
        
        $categories = $this->extractor->getData();
        
        $optionFieldName = $categories['healthcare_practitioners']['options']['dietitian_nutritionist']['field_name'];
        
        $this->assertEquals('ctl00$MainContentPlaceHolder$ckbRoster110', $optionFieldName);
    }
    
    public function testSave()
    {
        $filePath = codecept_output_dir('scrape/extracted/connecticut/connecticut-categories.json');
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $this->extractor->extract()->save();
        
        $this->assertFileExists($filePath);
        
        // assert file content
        $categories = json_decode(file_get_contents($filePath), true);
        
        $this->assertCount(36, $categories);
        $this->assertArrayHasKey('accountancy', $categories);
        $this->assertArrayHasKey('standards_home_heating_fuel_dealer_gas_stations_and_weighing_devices', $categories);
        $this->assertArrayHasKey('youth_camp_licensing', $categories);
    }
}