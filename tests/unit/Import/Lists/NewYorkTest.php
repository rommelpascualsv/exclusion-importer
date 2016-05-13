<?php
use App\Import\Lists\NewYork;

class NewYorkTest extends \Codeception\TestCase\Test
{
    private $newyork;

    protected function _before()
    {
        $this->newyork = new NewYork();
    }


    public function testParse()
    {
        $this->newyork->data = [
            [
                'ANTHONY J MOSCHETTO DO PC',
                '',
                '1407286552',
                'Physician Group',
                '2015/11/11',
                'Exclusion - 18NYCRR504.1(d)(1)'
            ]
        ];

        $this->newyork->preProcess();

        $expected = [
            [
                'business' => 'ANTHONY J MOSCHETTO DO PC',
                'provider_number' => '',
                'npi' => '1407286552',
                'provtype' => 'Physician Group',
                'action_date' => '2015/11/11',
                'action_type' => 'Exclusion - 18NYCRR504.1(d)(1)',
                'provider_number_2' => ''
            ]
        ];

        $this->assertEquals($expected, $this->newyork->data);
    }
}