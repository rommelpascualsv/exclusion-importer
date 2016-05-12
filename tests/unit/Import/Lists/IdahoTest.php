<?php

use App\Import\Lists\Idaho;

class IdahoTest extends \Codeception\TestCase\Test
{
    private $importer;
    
    protected function _before()
    {
        $this->importer = new Idaho();
    }
    
    public function testHeaderRowsShouldNotBeIncludedInData()
    {
    
        $this->importer->data = 
            "\"\",Exclusion,Date Eligible,Date,\r\n".
            "Name,Start,for,Reinstated,Additional Information\r\n".
            "\"\",Date,Reinstatement,,\r\n".
            "\"CORONA, VIRGINIA\",12/23/11,12/23/21,,Convicted of grand theft\r\n".
            "\"\",Exclusion,Date Eligible,Date,\r\n".
            "Name,Start,for,Reinstated,Additional Information\r\n".
            "\"\",Date,Reinstatement,,\r\n".
            "CRAMER CHIROPRACTIC,10/17/11,10/17/21,,Owner convicted of grand theft";
    
        $this->importer->preProcess();
    
        $expected = [
            [
                'name'                              => 'CORONA, VIRGINIA',
                'exclusion_start_date'              => '2011-12-23',
                'date_eligible_for_reinstatement'   => '2021-12-23',
                'date_reinstated'                   => '',
                'additional_information'            => 'Convicted of grand theft'
            ], [
                'name'                              => 'CRAMER CHIROPRACTIC',
                'exclusion_start_date'              => '2011-10-17',
                'date_eligible_for_reinstatement'   => '2021-10-17',
                'date_reinstated'                   => '',
                'additional_information'            => 'Owner convicted of grand theft'
                ]
        ];
    
        $this->assertEquals($expected, $this->importer->data);
    }
    
    public function testColumnsWithLineBreaksShouldBeParsedAsASingleLine()
    {
    
        $this->importer->data =
            "ACHIEVING A BETTER LIFE,1/23/06,1/23/16,,\"Endangerment of health and \rsafety of a patient\r\n".
            "ALTA ADDICTIONS AND \rMENTAL HEALTH SVCS,7/16/09,7/16/19,,Owner convicted of executing a \rscheme to defraud health care \rprogram";
    
        $this->importer->preProcess();
    
        $expected = [
            [
                'name'                              => 'ACHIEVING A BETTER LIFE',
                'exclusion_start_date'              => '2006-01-23',
                'date_eligible_for_reinstatement'   => '2016-01-23',
                'date_reinstated'                   => '',
                'additional_information'            => 'Endangerment of health and safety of a patient'
            ], [
                'name'                              => 'ALTA ADDICTIONS AND MENTAL HEALTH SVCS',
                'exclusion_start_date'              => '2009-07-16',
                'date_eligible_for_reinstatement'   => '2019-07-16',
                'date_reinstated'                   => '',
                'additional_information'            => 'Owner convicted of executing a scheme to defraud health care program'
            ]
        ];
    
        $this->assertEquals($expected, $this->importer->data);
    }   
    
    public function testIndefiniteReinstatementDateShouldBeParsedAsIndefinite()
    {
    
        $this->importer->data = "\"WINMILL, DIANA RENEE\",1/18/01,Indefinite,,Excluded by the OIG";
    
        $this->importer->preProcess();
    
        $expected = [
            [
                'name'                              => 'WINMILL, DIANA RENEE',
                'exclusion_start_date'              => '2001-01-18',
                'date_eligible_for_reinstatement'   => 'Indefinite',
                'date_reinstated'                   => '',
                'additional_information'            => 'Excluded by the OIG'
            ]
        ];
    
        $this->assertEquals($expected, $this->importer->data);
    }
    
    public function testRowsOverflowingToNextPageShouldBeParsedCorrectly()
    {
    
        $this->importer->data =
            //Row from previous page continued in next page
            "\"WINMILL, DIANA RENEE\",1/18/01,Indefinite,,Excluded by the OIG; 5-year\r\n".
            //Header rows signify a page break
            "\"\",Exclusion,Date Eligible,Date,\r\n".
            "Name,Start,for,Reinstated,Additional Information\r\n".
            "\"\",Date,Reinstatement,,\r\n".
            //Continuation of previous row in previous page
            "LPN (AKA FERRELL),,,,state exclusion";
        
        $this->importer->preProcess();
    
        $expected = [
            [
                'name'                              => 'WINMILL, DIANA RENEE LPN (AKA FERRELL)',
                'exclusion_start_date'              => '2001-01-18',
                'date_eligible_for_reinstatement'   => 'Indefinite',
                'date_reinstated'                   => '',
                'additional_information'            => 'Excluded by the OIG; 5-year state exclusion'
            ]
        ];
    
        $this->assertEquals($expected, $this->importer->data);
    }    
    
}