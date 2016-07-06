<?php

namespace Test\Unit;

use App\Import\Lists\Vermont;

class VermontTest extends \Codeception\TestCase\Test
{
    private $importer;

    protected function _before()
    {
        $this->importer = new Vermont();
    }

    public function testPreProcessShouldSkipHeaders() {

        $importer = $this->importer;

        $importer->data = [
            ['Provider ID','Provider NPI', 'Provider Full Name', 'Provider City', 'Provider State', 'Provider Status Code', 'Provider Status Code Description',	'Provider Status Effective Date','Provider Status End Date','Provider Type'],
            ['1878', '1396769733', 'SALVATORIELLO,FRED W DMD ','HANOVER ','NH', '9', 'ADMINISTRATION ACTION - INACTIVE ', '1/1/2009','12/31/2382','DENTIST ']
        ];

        $importer->preProcess();

        $expected = [
            [
                'provider_id' => '1878',
                'npi' => '1396769733',
                'full_name' => 'SALVATORIELLO,FRED W DMD',
                'city' => 'HANOVER',
                'state' => 'NH',
                'status_code' => '9',
                'status_code_desc' => 'ADMINISTRATION ACTION - INACTIVE',
                'status_effective_date' => '2009-01-01',
                'status_end_date' => '2382-12-31',
                'provider_type' => 'DENTIST'
            ]
        ];

        $this->assertEquals($expected, $importer->data);
    }

    public function testPreProcessWithMultipleNpi() {

        $importer = $this->importer;

        $importer->data = [
            ['1878', '1396769733 1396769734', 'SALVATORIELLO,FRED W DMD ','HANOVER ','NH', '9', 'ADMINISTRATION ACTION - INACTIVE ', '1/1/2009','12/31/2382','DENTIST ']
        ];

        $importer->preProcess();

        $expected = [
            [
                'provider_id' => '1878',
                'npi' => '["1396769733","1396769734"]',
                'full_name' => 'SALVATORIELLO,FRED W DMD',
                'city' => 'HANOVER',
                'state' => 'NH',
                'status_code' => '9',
                'status_code_desc' => 'ADMINISTRATION ACTION - INACTIVE',
                'status_effective_date' => '2009-01-01',
                'status_end_date' => '2382-12-31',
                'provider_type' => 'DENTIST'
            ]
        ];

        $this->assertEquals($expected, $importer->data);
    }

    public function testPreProcessWithNoNpi() {

        $importer = $this->importer;

        $importer->data = [
            ['1878', '', 'SALVATORIELLO,FRED W DMD ','HANOVER ','NH', '9', 'ADMINISTRATION ACTION - INACTIVE ', '1/1/2009','12/31/2382','DENTIST ']
        ];

        $importer->preProcess();

        $expected = [
            [
                'provider_id' => '1878',
                'npi' => '',
                'full_name' => 'SALVATORIELLO,FRED W DMD',
                'city' => 'HANOVER',
                'state' => 'NH',
                'status_code' => '9',
                'status_code_desc' => 'ADMINISTRATION ACTION - INACTIVE',
                'status_effective_date' => '2009-01-01',
                'status_end_date' => '2382-12-31',
                'provider_type' => 'DENTIST'
            ]
        ];

        $this->assertEquals($expected, $importer->data);
    }
}
