<?php
namespace Import\Scrape\Scrapers\Connecticut;


use App\Import\Scrape\Scrapers\Connecticut\MainPage;
use Goutte\Client;

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
    public function testMe()
    {
    	/* $this->page = MainPage::scrape($this->client); */
    }
}