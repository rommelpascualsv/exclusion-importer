<?php
namespace Import\Scrape\Scrapers\Connecticut;


use App\Import\Scrape\Scrapers\Connecticut\MainPage;
use Goutte\Client;
use App\Import\Scrape\Scrapers\Connecticut\Data\CategoryCollection;
use Symfony\Component\DomCrawler\Form;
use App\Import\Scrape\Scrapers\Connecticut\Data\Option;
use App\Exceptions\Scrape\ScrapeException;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;

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
    	$options = new OptionCollection([new Option('undefined option name', 'asdasdas', 'asdasdsa', 'asdasds')]);
    	 
    	$form = $this->page->tickOptions($options);
    }
}