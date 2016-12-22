<?php

use App\Import\Lists\WestVirginia;

class WestVirginiaTest extends \Codeception\TestCase\Test
{
    private $westvirginia;

    protected function _before()
    {
        $this->westvirginia = new WestVirginia;
    }

    public function testParse()
    {

        $this->westvirginia->data = '"",Sheila Jean Brooks,Sheila,Jean,Brooks,,DPM,Physician,Princeton,WV,8/10/2015,Voluntary Surrender of License,,';

        $this->westvirginia->preProcess();

        $expected = [
            [
                'npi_number' => '',
                'full_name' => '',
                'first_name' => 'Sheila',
                'middle_name' => 'Jean',
                'last_name' => 'Brooks',
                'generation' => '',
                'credentials' => 'DPM',
                'provider_type' => 'Physician',
                'city' => 'Princeton',
                'state' => 'WV',
                'exclusion_date' => '2015-08-10',
                'reason_for_exclusion' => 'Voluntary Surrender of License',
                'reinstatement_date' => '',
                'reinstatement_reason' => '',
                'provider_number' => ''
            ]
        ];

        $this->assertEquals($expected, $this->westvirginia->data);
    }
}
