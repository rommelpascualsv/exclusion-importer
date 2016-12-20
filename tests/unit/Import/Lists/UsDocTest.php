<?php

use App\Import\Lists\USDOCDeniedPersons;

class USDOCDeniedPersonsTest extends \Codeception\TestCase\Test
{
    private $usdoc;

    protected function _before()
    {
        $this->usdoc = new USDOCDeniedPersons;
    }

    public function testParse()
    {
        $this->usdoc->data = [
            [
                'A. ROSENTHAL (PTY) LTD.',
                'P.O. BOX 44198, 65 7TH STREET, DENMYR BUILDING',
                'LINDEN',
                '',
                'ZA',
                '2104',
                '1997/08/08',
                '2017/08/08',
                'Y',
                '2000/03/23',
                '',
                '62 F.R. 43503 8/14/97'
            ]
        ];

        $this->usdoc->preProcess();

        $expected = [
            [
                'name' => 'A. ROSENTHAL (PTY) LTD.',
                'street_address' => 'P.O. BOX 44198, 65 7TH STREET, DENMYR BUILDING',
                'city' => 'LINDEN',
                'state' => '',
                'country' => 'ZA',
                'postal_code' => '2104',
                'effective_date' => '1997-08-08',
                'expiration_date' => '2017-08-08',
                'last_update' => '2000-03-23'
            ]
        ];

        $this->assertEquals($expected, $this->usdoc->data);
    }
}
