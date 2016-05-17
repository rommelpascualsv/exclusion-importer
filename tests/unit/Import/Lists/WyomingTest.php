<?php namespace Test\Unit;

use App\Import\Lists\Wyoming;

class WyomingTest extends \Codeception\TestCase\Test
{
    private $importer;
    
    protected function _before()
    {
        $this->importer = new Wyoming();
    }
    
    public function testHeaderRowShouldNotBeIncludedInData()
    {
        $this->importer->data = 
            "\"Last Name \",\"First Name \",\"Business Name \",\"Provider Type \",\"Provider Number \",\"City \",\"Stat \",Exclusion Dat\r\n".
            "\"\",,\"Kare's Home Health \",\"Case Management Waiver \",\"120222700 \",\"Bar Nunn \",\"WY \",4/28/2007\r\n".
            "\"Last Name \",\"First Name \",\"Business Name \",\"Provider Type \",\"Provider Number \",\"City \",\"Stat \",Exclusion Dat\r\n".
            "\"Adekale \",\"Adebowale aka Ted \",\"JY Adekale Global Initiative \",\"Training DD Waiver \",\"127297700 \",\"Cheyenne \",\"WY \",8/10/2011";
        
        $this->importer->preProcess();
        
        $expected = [
            [
                'last_name'         => '',
                'first_name'        => '',
                'business_name'     => 'Kare\'s Home Health',
                'provider_type'     => 'Case Management Waiver',
                'provider_number'   => '120222700',
                'city'              => 'Bar Nunn',
                'state'             => 'WY',
                'exclusion_date'    => '2007-04-28',
                'npi'               => null
            ],[
                'last_name'         => 'Adekale',
                'first_name'        => 'Adebowale aka Ted',
                'business_name'     => 'JY Adekale Global Initiative',
                'provider_type'     => 'Training DD Waiver',
                'provider_number'   => '127297700',
                'city'              => 'Cheyenne',
                'state'             => 'WY',
                'exclusion_date'    => '2011-08-10',
                'npi'               => null
            ]
        ];
        
        $this->assertEquals($expected, $this->importer->data);        
    }

    public function testMultiLineColumnsShouldBeParsedAsASingleLine()
    {
        $this->importer->data =
            // Multi-line Provider Number
            "\"Ketcham \",\"Deborah A \",,\"Nurse/Nurses Assistant \",\"RN#21161, \",\"Cheyenne \",\"WY \",7/31/2012\r\n".
            "\"\",,,,\"LPN#5579, \",,,\r\n".
            "\"\",,,,CO#173270,,,\r\n";    
        
        $this->importer->preProcess();
    
        $expected = [
            [
                'last_name'         => 'Ketcham',
                'first_name'        => 'Deborah A',
                'business_name'     => '',
                'provider_type'     => 'Nurse/Nurses Assistant',
                'provider_number'   => 'RN#21161, LPN#5579, CO#173270',
                'city'              => 'Cheyenne',
                'state'             => 'WY',
                'exclusion_date'    => '2012-07-31',
                'npi'               => null
            ]
        ];
    
        $this->assertEquals($expected, $this->importer->data);
    }
    
    public function testNPINumberShouldBeExtractedFromProviderNumberAndStoredAsAnotherColumn()
    {
        $this->importer->data =
            // '<npi_number>NPI' format 
            "\"Raynor \",\"Alice \",\"Alice Raynor Nursing Services, Inc. \",\"Nurse/Nurses Assistant \",\"1992908941NPI \",\"Westfield \",\"NY \",10/31/2011\r\n".
            // 'NPI<npi_number format>
            "\"Woods \",\"Medea Laurel \",,\"Psychologist \",\"NPI 1881890291; \",\"Rawlins \",\"WY \",2/20/2014\r\n".
            //Multi-line provider number with NPI included
            "\"Brown \",\"Craig S \",,\"Medical Doctor \",\"1087711 00 WY Prov \",\"Cheyenne \",\"WY \",7/20/2015\r\n".
            "\"\",,,,\"# NPI 1740290253\",,,";
            
        $this->importer->preProcess();
    
        $expected = [
            [
                'last_name'         => 'Raynor',
                'first_name'        => 'Alice',
                'business_name'     => 'Alice Raynor Nursing Services, Inc.',
                'provider_type'     => 'Nurse/Nurses Assistant',
                'provider_number'   => '',
                'city'              => 'Westfield',
                'state'             => 'NY',
                'exclusion_date'    => '2011-10-31',
                'npi'               => '1992908941'
            ], [
                'last_name'         => 'Woods',
                'first_name'        => 'Medea Laurel',
                'business_name'     => '',
                'provider_type'     => 'Psychologist',
                'provider_number'   => '',
                'city'              => 'Rawlins',
                'state'             => 'WY',
                'exclusion_date'    => '2014-02-20',
                'npi'               => '1881890291'
            ], [
                'last_name'         => 'Brown',
                'first_name'        => 'Craig S',
                'business_name'     => '',
                'provider_type'     => 'Medical Doctor',
                'provider_number'   => '1087711 00 WY Prov #',
                'city'              => 'Cheyenne',
                'state'             => 'WY',
                'exclusion_date'    => '2015-07-20',
                'npi'               => '1740290253'
                
            ]
        ];
    
        $this->assertEquals($expected, $this->importer->data);
    }    
    
}
