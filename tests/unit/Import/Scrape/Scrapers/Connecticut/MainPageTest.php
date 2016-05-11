<?php
namespace Import\Scrape\Scrapers\Connecticut;


use App\Import\Scrape\Scrapers\Connecticut\MainPage;
use Goutte\Client;
use App\Import\Scrape\Scrapers\Connecticut\Data\CategoryCollection;
use Symfony\Component\DomCrawler\Form;
use App\Import\Scrape\Scrapers\Connecticut\Data\Option;
use App\Exceptions\Scrape\ScrapeException;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;
use App\Import\Scrape\Scrapers\Connecticut\Data\Category;

class MainPageTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    	$this->client = $this->getModule('Lumen')->app->make(Client::class);
    }

    protected function _after()
    {
    }

    // tests
    public function testTickOptions()
    {
    	$this->page = MainPage::scrape($this->client);
    	
    	/** @var CategoryCollection $categories */
    	$categories = $this->getModule('Lumen')->app->make(CategoryCollection::class);
    	$options = $categories->getOptions(['accountancy']);
    	
    	$form = $this->page->tickOptions($options);
    	
    	$this->assertInstanceOf(Form::class, $form);
    }
    
    /**
     * @expectedException App\Exceptions\Scrape\ScrapeException
     * @expectedExceptionMessage undefined option name option cannot be found
     */
    public function testTickOptionsFailed()
    {
    	$this->page = MainPage::scrape($this->client);
    	 
    	/** @var CategoryCollection $categories */
    	$options = new OptionCollection([new Option('undefined option name', 'asdasdas', 'asdasdsa', new Category('asdas', 'asdas'))]);
    	 
    	$form = $this->page->tickOptions($options);
    }
    
    public function testGetCategoryText()
    {
        $this->page = MainPage::scrape($this->client);
        $panelNode = $this->page->getPanelNodes()->eq(0);
        $i = 0;
        
        $category = $this->page->getCategoryText($panelNode, $i);
        
        $this->assertSame('Accountancy', $category);
    }
    
    public function testGetOptionsData()
    {
        $this->page = MainPage::scrape($this->client);
        $panelNode = $this->page->getPanelNodes()->eq(0);
        $i = 0;
        
        $optionData = $this->page->getOptionsData($panelNode, $i);
        $expectedOptionData = [
            [
                'name' => 'Certified Public Accountant Certificate',
                'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster0'
            ],
            [
                'name' => 'Certified Public Accountant Firm Permit',
                'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster1'
            ],
            [
                'name' => 'Certified Public Accountant License',
                'field_name' => 'ctl00$MainContentPlaceHolder$ckbRoster2'
            ]
        ];
        
        $this->assertSame($expectedOptionData, $optionData);
    }
}