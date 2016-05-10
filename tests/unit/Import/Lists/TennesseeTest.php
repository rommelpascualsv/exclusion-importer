<?php 
use App\Import\Lists\Tennessee;

class TennesseeTest extends \Codeception\TestCase\Test
{
    private $importer;
 
    protected function _before()
    {
        $this->importer = new Tennessee();
    }
    
    public function testHeaderRowShouldNotBeIncludedInData()
    {
        
        $this->importer->data = "Last Name,First Name,NPI,Begin Date,Reason,End Date\r\n".
                          "Foster,Allen R.,1326132572,3/20/2012,Felony conviction,\r\n".
                          "Last Name,First Name,NPI,Begin Date,Reason,End Date\r\n".
                          'Blankenship,Brad,1427090562,7/30/2015,Failure to respond to requests for records on Tenncare patients,7/30/2018';
        
        $this->importer->preProcess();
        
        $expected = [
            [
                'last_name' => 'Foster',
                'first_name' => 'Allen',
                'middle_name' => 'R.',
                'npi' => '1326132572',
                'begin_date' => '2012-03-20',
                'reason' => 'Felony conviction',
                'end_date' => null
            ], [
                'last_name' => 'Blankenship',
                'first_name' => 'Brad',
                'middle_name' => '',
                'npi' => '1427090562',
                'begin_date' => '2015-07-30',
                'reason' => 'Failure to respond to requests for records on Tenncare patients',
                'end_date' => '2018-07-30'
            ]            
        ];
        
        $this->assertEquals($expected, $this->importer->data);
        
    }    
    
    public function testMiddleNamesShouldBeParsedFromFirstNameCorrectly()
    {
    
        $this->importer->data = 
            "Foster,Allen R.,1326132572,3/20/2012,Felony conviction,\r\n". //Middle Initial
            "Blankenship,Brad Pitt,1427090562,7/30/2015,Failure to respond to requests for records on Tenncare patients,7/30/2018\r\n". //Middle Name
            "Doe,Mary Jane X.,1427090564,6/30/2016,Violation of the contract,6/30/2019\r\n". //Middle Name with Middle Initial
            'Clabough,Kenneth,1043631401,10/30/2015,Failure to disclose required information,'; //No middle name
        
        $this->importer->preProcess();
    
        $expected = [
            [
                'last_name' => 'Foster',
                'first_name' => 'Allen',
                'middle_name' => 'R.',
                'npi' => '1326132572',
                'begin_date' => '2012-03-20',
                'reason' => 'Felony conviction',
                'end_date' => null
            ], [
                'last_name' => 'Blankenship',
                'first_name' => 'Brad',
                'middle_name' => 'Pitt',
                'npi' => '1427090562',
                'begin_date' => '2015-07-30',
                'reason' => 'Failure to respond to requests for records on Tenncare patients',
                'end_date' => '2018-07-30'
            ],[
                'last_name' => 'Doe',
                'first_name' => 'Mary',
                'middle_name' => 'Jane X.',
                'npi' => '1427090564',
                'begin_date' => '2016-06-30',
                'reason' => 'Violation of the contract',
                'end_date' => '2019-06-30'
            ],[
                'last_name' => 'Clabough',
                'first_name' => 'Kenneth',
                'middle_name' => '',
                'npi' => '1043631401',
                'begin_date' => '2015-10-30',
                'reason' => 'Failure to disclose required information',
                'end_date' => null                
            ]
        ];
    
        $this->assertEquals($expected, $this->importer->data);
    
    }
    
    public function testOverflowingNamesShouldBeProcessedCorrectly()
    {
    
        $this->importer->data = 'The Rainbow Center of Children & Adole,scent,1073705141,3/31/2016,Violation of the contract.,';
    
        $this->importer->preProcess();
    
        $expected = [[
            'last_name' => 'The Rainbow Center of Children & Adolescent',
            'first_name' => '',
            'middle_name' => '',
            'npi' => '1073705141',
            'begin_date' => '2016-03-31',
            'reason' => 'Violation of the contract.',
            'end_date' => null
        ]];
        
        $this->assertEquals($expected, $this->importer->data);
    
    }    

}
