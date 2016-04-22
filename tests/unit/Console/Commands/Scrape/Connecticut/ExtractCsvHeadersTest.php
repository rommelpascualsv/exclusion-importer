<?php
namespace Console\Commands\Scrape\Connecticut;


use App\Console\Commands\Scrape\Connecticut\ExtractCsvHeaders;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class ExtractCsvHeadersTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    	$this->command = new ExtractCsvHeaders();
    	$this->command->setLaravel($this->tester->getLumenApp());
    }

    protected function _after()
    {
    }

    // tests
    public function testHandle()
    {
    	$this->runCommand(); 
    }
    
    protected function runCommand()
    {
    	$this->command->run(new ArrayInput([]), new ConsoleOutput());
    }
}