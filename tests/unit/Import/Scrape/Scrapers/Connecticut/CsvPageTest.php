<?php
namespace Import\Scrape\Scrapers\Connecticut;


use App\Import\Scrape\Scrapers\Connecticut\CsvPage;

class CsvPageTest extends \Codeception\TestCase\Test
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
    public function testGetUrl()
    {
		$url = CsvPage::getUrl('51232');
		
		$this->assertEquals(
				'https://www.elicense.ct.gov/Lookup/FileDownload.aspx?Idnt=51232&Type=Comma',
				$url
		);
    }
}