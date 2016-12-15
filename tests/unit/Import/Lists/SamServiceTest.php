<?php namespace Test\Unit;

use App\Import\Lists\Sam\SamRepository;
use App\Import\Lists\Sam\SamService;
use App\Services\ExclusionListHttpDownloader;

class SamServiceTest extends \Codeception\Test\Unit
{
    protected $tester;

    public function _before()
    {
        $this->tester = new SamService();
    }

    public function _after()
    {
    }


    public function testShouldGetFileName()
    {
        $expected = 'SAM_Exclusions_Public_Extract_16340';
        $actual = $this->tester->getFileName();
        $this->assertEquals($expected, $actual);
    }


    public function testShouldGetUrl()
    {
        $expected =  'https://www.sam.gov/public-extracts/SAM-Public/SAM_Exclusions_Public_Extract_16340.ZIP';
        $actual = $this->tester->getUrl();

        $this->assertEquals($expected, $actual);
    }

    public function testExtractZip()
    {
        $zipLocation = 'tests/_data/' .
            'SAM_Exclusions_Public_Extract_16325.ZIP';

        $expected = true;
        //$actual = false;
        $actual = $this->tester->extractZip($zipLocation);

        $this->assertEquals($expected, $actual);
    }
}

