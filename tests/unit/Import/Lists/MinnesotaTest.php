<?php

use App\Import\Lists\Minnesota;

class MinnesotaTest extends \Codeception\TestCase\Test
{
    private $minnesota;

    protected function _before()
    {
        $this->minnesota = new Minnesota;
    }

    public function testParseIndividual()
    {
        $this->minnesota->data = [
            [
                'DENTIST',
                'JONES',
                'ROBERT',
                'W',
                '11/13/2014',
                '',
                '111 E KELLOGG BLVD #205',
                'ST PAUL',
                'MN',
                '55101'
            ]
        ];

        $this->minnesota->preProcess();

        $expected = [
            [
                'provider_type_description' => 'DENTIST',
                'sort_name' => '',
                'last_name' => 'JONES',
                'first_name' => 'ROBERT',
                'middle_name' => 'W',
                'effective_date_of_exclusion' => '2014-11-13',
                'address_line1' => '',
                'address_line2' => '111 E KELLOGG BLVD #205',
                'city' => 'ST PAUL',
                'state' => 'MN',
                'zip' => '55101'
            ]
        ];

        $this->assertEquals($expected, $this->minnesota->data);
    }

    public function testParseEntity()
    {
        $this->minnesota->data = [
            [
                'PRIVATE DUTY NURSE',
                'HOPECARE SERVICES INC',
                '7/6/2012',
                '7710 BROOKLYN BLVD',
                'STE 109',
                'BROOKLYN PARK',
                'MN',
                '55443'
            ]
        ];

        $this->minnesota->preProcess();

        $expected = [
            [
                'provider_type_description' => 'PRIVATE DUTY NURSE',
                'sort_name' => 'HOPECARE SERVICES INC',
                'last_name' => '',
                'first_name' => '',
                'middle_name' => '',
                'effective_date_of_exclusion' => '2012-07-06',
                'address_line1' => '7710 BROOKLYN BLVD',
                'address_line2' => 'STE 109',
                'city' => 'BROOKLYN PARK',
                'state' => 'MN',
                'zip' => '55443'
            ]
        ];

        $this->assertEquals($expected, $this->minnesota->data);
    }
}
