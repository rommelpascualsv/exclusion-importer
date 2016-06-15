<?php

use App\Import\Lists\Nevada;

class NevadaTest extends \Codeception\TestCase\Test
{
    private $importer;
    
    protected function _before()
    {
        $this->importer = new Nevada();
    }
    
    public function testRemoveHeaders()
    {
        $this->importer->data = $this->getInputData();
        
        $headers = [
        	',,Persons with controlling inerest of,Medicaid,,Provider,,Sanction,Sanction,,',
        	'Business Name,Legal Entity,5% or more,Provider,NPI,Type,Date,Tier,Period,,'
        ];
        
        $this->importer->preProcess();
        
        foreach ($this->importer->data as $row) {
            
            $row = array_values($row);
            
            $record = implode(',', $row);
            $this->assertFalse(in_array($record, $headers), "Data contains header values.");
        }
    }
    
    public function testRemoveFooter()
    {
        $this->importer->data = $this->getInputData();
    
        $this->importer->preProcess();
    
        foreach ($this->importer->data as $row) {
    
            $row = array_values($row);
    
            $record = implode(',', $row);
            $this->assertFalse($record === '"",,,,"Page 1 ",of 7,,,,,', "Data contains footer values.");
        }
    }
    
    public function testValidConversion()
    {
        $this->importer->data = $this->getInputData();
    
        $this->importer->preProcess();
    
        $expected = [
            [
                'doing_business_as'                 => "Charlene DeMarco",
                'legal_entity'                      => "",
                'ownership_of_at_least_5_percent'   => "",
                'medicaid_provider'                 => "",
                'npi'                               => "1234567890",
                'provider_type'                     => "",
                'termination_date'                  => "2008-07-20",
                'sanction_tier'                     => "Federal",
                'sanction_period'                   => "Permanent",
                'sanction_period_end_date'          => null,
                'reinstatement_date'                => null,
                'provider_number'                   => '',
                'aka'                               => ''
                
            ],
            [
                'doing_business_as'                 => "Elijah Akpan",
                'legal_entity'                      => "",
                'ownership_of_at_least_5_percent'   => "",
                'medicaid_provider'                 => "",
                'npi'                               => "[\"1234567890\",\"1111111111\"]",
                'provider_type'                     => "",
                'termination_date'                  => "2010-10-20",
                'sanction_tier'                     => "Federal",
                'sanction_period'                   => "Permanent",
                'sanction_period_end_date'          => null,
                'reinstatement_date'                => null,
                'provider_number'                   => '9090909012 101010101 Not Available 00011100',
                'aka'                               => '["Charlie Villa","Richard Gere"]'
            ]
        ];
        
        $this->assertEquals($expected, $this->importer->data);
    }
    
    public function testExtractAliasesFromNameColumn()
    {
        $this->importer->data = 'John Smith aka Jonathan Smith,,,,1234567890,,7/20/2008,Federal,Permanent,,' . PHP_EOL .
                                'Charlene DeMarco aka Jane Doe & Jason Miller,,,,1234567890,,7/20/2008,Federal,Permanent,,' . PHP_EOL;
        
        $this->importer->preProcess();
        
        $this->assertEquals('["Jonathan Smith"]', $this->importer->data[0]['aka']);
        $this->assertEquals('John Smith', $this->importer->data[0]['doing_business_as']);
        
        $this->assertEquals('["Jane Doe","Jason Miller"]', $this->importer->data[1]['aka']);
        $this->assertEquals('Charlene DeMarco', $this->importer->data[1]['doing_business_as']);
    }
    
    private function getInputData()
    {
        return '"",,,,,,,,,,Mar 2016' . PHP_EOL . 
            '"",,,,,,,,,"NV ",' . PHP_EOL .
            '"",,,,,,,,,"Medicaid ",' . PHP_EOL .
            '"",,"Persons with ",,,,,,,"Sanction ","Federal "' . PHP_EOL .
            '"",,"controlling inerest of ","Medicaid ",,"Provider ","Termination ","Sanction ","Sanction ","Period End ","Reinstate "' . PHP_EOL .
            'Business Name,Legal Entity,5% or more,Provider,NPI,Type,Date,Tier,Period,Date,Date' . PHP_EOL .
            'Charlene DeMarco,,,,1234567890,,7/20/2008,Federal,Permanent,,' . PHP_EOL .
            'Elijah Akpan aka Charlie Villa & Richard Gere,,,,9090909012 101010101 Not Available 1234567890 00011100 1111111111,,10/20/2010,Federal,Permanent,,' . PHP_EOL .
            '"",,,,"Page 1 ",of 7,,,,,' . PHP_EOL;
    }
}
