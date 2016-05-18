<?php
use App\Import\Lists\Michigan;

class NewJerseyTest extends \Codeception\TestCase\Test
{
    private $michigan;

    protected function _before()
    {
        $this->michigan = new Michigan();
    }


    public function testParse()
    {
        $this->michigan->data = [
            [
                'A Dental Center PC - Allen Park',
                '',
                '',
                '',
                'Dental Group',
                '',
                'Allen Park',
                '',
                '2004/05/20',
                'HHS',
                '',
                '',
                '1128(a)(1)'
            ],
            [
                'A Dental Center PC - Southgate',
                '',
                '',
                '',
                'Dental Group',
                '',
                'Southgate',
                '',
                '2004/07/20',
                '',
                '',
                '',
                '1128(a)(1)'
            ]
        ];

        $this->michigan->preProcess();

        $expected = [
            [
                'entity_name' => 'A Dental Center PC - Allen Park',
                'last_name' => '',
                'first_name' => '',
                'middle_name' => '',
                'provider_category' => 'Dental Group',
                'npi_number' => '',
                'city' => 'Allen Park',
                'license_number' => '',
                'sanction_date_1' => '2004-05-20',
                'sanction_source_1' => 'HHS',
                'sanction_date_2' => '',
                'sanction_source_2' => '',
                'reason' => '1128(a)(1)',
                'provider_number' => ''
            ],
            [
                'entity_name' => 'A Dental Center PC - Southgate',
                'last_name' => '',
                'first_name' => '',
                'middle_name' => '',
                'provider_category' => 'Dental Group',
                'npi_number' => '',
                'city' => 'Southgate',
                'license_number' => '',
                'sanction_date_1' => '2004-07-20',
                'sanction_source_1' => '',
                'sanction_date_2' => '',
                'sanction_source_2' => '',
                'reason' => '1128(a)(1)',
                'provider_number' => ''
            ]
        ];

        $this->assertEquals($expected, $this->michigan->data);
    }
}
