<?php
use App\Import\Lists\Missouri;

class NewJerseyTest extends \Codeception\TestCase\Test
{
    private $missouri;

    protected function _before()
    {
        $this->missouri = new Missouri();
    }


    public function testParse()
    {
        $this->missouri->data = [
            [
                '2014/07/11',
                '2014/07/09',
                'ADDS HEALTH SERVICES',
                '1902890353',
                '58-HOME HEALTH AGENCY',
                '756',
                'FRAUD'
            ],
            [
                '2014/07/11',
                '2014/07/09',
                'ADDS HEALTH SERVICES',
                '1902890353',
                '27-STATE DESIGNEE',
                '',
                'FRAUD'
            ],
            [
                '2013/03/27',
                '2013/02/27',
                'ADULT DAYCARE VILLAS',
                '1215186051',
                '29-ADULT DAY HEALTH CARE',
                '',
                'SETTLEMENT AGREEMENT'
            ]
        ];

        $this->missouri->preProcess();

        $expected = [
            [
                'termination_date' => '2014-07-11',
                'letter_date' => '2014-07-09',
                'provider_name' => 'ADDS HEALTH SERVICES',
                'npi' => '1902890353',
                'provider_type' => '58-HOME HEALTH AGENCY',
                'license_number' => '756',
                'termination_reason' => 'FRAUD',
                'provider_number' => ''
            ],
            [
                'termination_date' => '2014-07-11',
                'letter_date' => '2014-07-09',
                'provider_name' => 'ADDS HEALTH SERVICES',
                'npi' => '1902890353',
                'provider_type' => '27-STATE DESIGNEE',
                'license_number' => '',
                'termination_reason' => 'FRAUD',
                'provider_number' => ''
            ],
            [
                'termination_date' => '2013-03-27',
                'letter_date' => '2013-02-27',
                'provider_name' => 'ADULT DAYCARE VILLAS',
                'npi' => '1215186051',
                'provider_type' => '29-ADULT DAY HEALTH CARE',
                'license_number' => '',
                'termination_reason' => 'SETTLEMENT AGREEMENT',
                'provider_number' => '',
            ]
        ];

        $this->assertEquals($expected, $this->missouri->data);
    }
}
