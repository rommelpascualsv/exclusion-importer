<?php

use App\Import\Lists\Washington;

class WashingtonTest extends \Codeception\TestCase\Test
{
    private $washington;

    protected function _before()
    {
        $this->washington = new Washington;
    }

    public function testParseBusiness()
    {
        unset($this->washington->retrieveOptions['offset']);

        $this->washington->data = 'Cedarcrest Adult Family Ho,AFH,Not Available,2/11/2016,DSHS Termination';

        $this->washington->preProcess();

        $expected = [
            [
                'last_name' => '',
                'first_name' => '',
                'middle_etc' => '',
                'entity' => 'Cedarcrest Adult Family Ho',
                'provider_license' => 'AFH',
                'npi' => '',
                'termination_date' => '2016-02-11',
                'termination_reason' => 'DSHS Termination',
                'provider_number' => 'Not Available'
            ]
        ];

        $this->assertEquals($expected, $this->washington->data);
    }

    public function testParseIndividual()
    {
        unset($this->washington->retrieveOptions['offset']);

        $this->washington->data = '"Adams, Brandon MD",MD00026765,1295888063,12/16/2011,License Suspended';

        $this->washington->preProcess();

        $expected = [
            [
                'last_name' => 'Adams',
                'first_name' => 'Brandon',
                'middle_etc' => 'MD',
                'entity' => '',
                'provider_license' => 'MD00026765',
                'npi' => '1295888063',
                'termination_date' => '2011-12-16',
                'termination_reason' => 'License Suspended',
                'provider_number' => ''
            ]
        ];

        $this->assertEquals($expected, $this->washington->data);
    }
}
