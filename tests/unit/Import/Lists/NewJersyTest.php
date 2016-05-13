<?php
use App\Import\Lists\NewJersey;

class NewJersyTest extends \Codeception\TestCase\Test
{
    private $newjersy;

    protected function _before()
    {
        $this->newjersy = new NewJersey();
    }


    public function testParse()
    {
        $this->newjersy->data = [
            [
                '',
                'ABDOLLAHI, MITRA  DMD',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '646 N. SARATOGA DRIVE',
                'MOORESTOWN',
                'NJ',
                '8057',
                '',
                'MEDICAL',
                'DISQUALIFICATION',
                'K',
                '54',
                '7540',
                '2007/11/05',
                '2016/02/04',
                'N'
            ]
        ];

        $this->newjersy->preProcess();

        $expected = [
            [
                'firm_name' => '',
                'name' => 'ABDOLLAHI, MITRA  DMD',
                'vendor_id' => '',
                'firm_street' => '',
                'firm_city' => '',
                'firm_state' => '',
                'firm_zip' => '',
                'firm_plus4' => '',
                'npi' => '',
                'street' => '646 N. SARATOGA DRIVE',
                'city' => 'MOORESTOWN',
                'state' => 'NJ',
                'zip' => '8057',
                'plus4' => '',
                'category' => 'MEDICAL',
                'action' => 'DISQUALIFICATION',
                'reason' => 'K',
                'debarring_dept' => '54',
                'debarring_agency' => '7540',
                'effective_date' => '2007-11-05',
                'expiration_date' => '2016-02-04',
                'permanent_debarment' => 'N',
                'provider_number' => ''
            ]
        ];

        $this->assertEquals($expected, $this->newjersy->data);
    }
}
