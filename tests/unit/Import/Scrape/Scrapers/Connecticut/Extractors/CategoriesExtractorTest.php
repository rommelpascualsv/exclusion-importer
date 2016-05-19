<?php
namespace Import\Scrape\Scrapers\Connecticut\Extractors;

use App\Import\Scrape\Scrapers\Connecticut\Extractors\CategoriesExtractor;
use Goutte\Client;

class CategoriesExtractorTest extends \Codeception\TestCase\Test
{	
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var CategoriesExtractor
     */
    protected $extractor;
    
    protected function _before()
    {
        $this->client = app(Client::class);
        $this->filesystem = app('scrape_test_filesystem');
        $this->extractor = new CategoriesExtractor($this->filesystem);
    }

    protected function _after()
    {
    }
    
    /**
     * Test extracting one category data from the main page
     */
    public function testExtractCategoryData()
    {   
        $headingNode = $this->extractor->scrapeMainPage($this->client)
            ->getPanelNodes()
            ->eq(0);
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
    
    /**
     * Test extracting all the categories from the main page
     */
    public function testExtract()
    {
        $this->extractor->extract($this->client);
        
        $categories = $this->extractor->getData();
        
        $this->assertCount(36, $categories);
        $this->assertArrayHasKey('accountancy', $categories);
        $this->assertArrayHasKey(
            'standards_home_heating_fuel_dealer_gas_stations_and_weighing_devices',
            $categories
        );
        $this->assertArrayHasKey('youth_camp_licensing', $categories);
    }
    
    /**
     * Test extract to get the first option if there is a duplicate
     */
    public function testExtractGetFirstOptionOnDuplicate()
    {
        $this->extractor->extract($this->client);
        
        $categories = $this->extractor->getData();
        
        $optionFieldName = $categories['healthcare_practitioners']['options']['dietitian_nutritionist']['field_name'];
        
        $this->assertEquals(
            'ctl00$MainContentPlaceHolder$ckbRoster110',
            $optionFieldName
        );
    }
    
    /**
     * Test if the file is saved in the correct directory and if it contains
     * the right contents
     */
    public function testSave()
    {
        $filePath = codecept_output_dir('scrape/extracted/connecticut/connecticut-categories.json');
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $this->extractor->extract($this->client)->save();
        
        $this->assertFileExists($filePath);
        
        // assert file content
        $categories = json_decode(file_get_contents($filePath), true);
        
        $this->assertCount(36, $categories);
        $this->assertArrayHasKey('accountancy', $categories);
        $this->assertArrayHasKey('standards_home_heating_fuel_dealer_gas_stations_and_weighing_devices', $categories);
        $this->assertArrayHasKey('youth_camp_licensing', $categories);
    }
}