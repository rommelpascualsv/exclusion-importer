<?php namespace Test\Unit;

use App\Import\Lists\Sam\SamRepository;

class SamServiceTest extends \Codeception\TestCase\Test
{
    protected $importer;

    public function _before()
    {
        $this->importer = new SamRepository();
    }

    public function shouldGenerateUrl()
    {
        $expected =  'https://www.sam.gov/public-extracts/SAM-Public/SAM_Exclusions_Public_Extract_16329.ZIP';
        $actual = $this->importer->getUrl();

        $this->assertEquals($expected, $actual);
    }


    public function shouldGetSamRecordsFromSource()
    {

    }

}

