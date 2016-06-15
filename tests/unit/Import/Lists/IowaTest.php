<?php namespace Test\Unit;

use App\Import\Lists\Iowa;

class IowaTest extends \Codeception\TestCase\Test
{

    /**
     * @var Iowa
     */
    protected $importer;

    public function _before()
    {
        $this->importer = new Iowa();
    }

    public function testRowsWithNAValuesAreTurnedToEmptyString()
    {
        $this->importer->data = [
            ['2/23/2015','N/A','Aldrich','Brad Lynn', 'N/A', 'Termination from Participation', '']
        ];

        $this->importer->preProcess();

        $expected = [
            [
                'sanction_start_date' => '2015-02-23',
                'npi' => '',
                'individual_last_name' => 'Aldrich',
                'individual_first_name' => 'Brad Lynn',
                'entity_name' => '',
                'sanction' => 'Termination from Participation',
                'sanction_end_date' => null,
                'provider_number' => ''
            ]
        ];

        $this->assertEquals($expected, $this->importer->data);
    }
}
