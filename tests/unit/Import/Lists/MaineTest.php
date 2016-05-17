<?php namespace Test\Unit;

use App\Import\Lists\Maine;

class MaineTest extends \Codeception\TestCase\Test
{
    private $importer;
    
    protected function _before()
    {
        $this->importer = new Maine();
    }
    
    public function testExcessColumnsShouldbeIgnored()
    {
        $this->importer->data = [
            ["ADAMS","AUDREY","A.","","","","","","","","","40-Nurse","Closed-Exclusion","2005/05/26"],
            ["ADAMS","LAURIE","","","","","","","","","","40-Nurse","Closed-Exclusion","2009/11/30"]
        ];
        
        $this->importer->preProcess();
        
        $expected = [
            [
                'entity'                => '',
                'last_name'             => 'ADAMS',
                'first_name'            => 'AUDREY',
                'middle_initial'        => 'A.',
                'prov_type'             => '40-Nurse',
                'case_status'           => 'Closed-Exclusion',
                'sanction_start_date'   => '2005-05-26',
                'aka_list'              => '[]'
            ]
            ,[
                'entity'                => '',
                'last_name'             => 'ADAMS',
                'first_name'            => 'LAURIE',
                'middle_initial'        => '',
                'prov_type'             => '40-Nurse',
                'case_status'           => 'Closed-Exclusion',
                'sanction_start_date'   => '2009-11-30',
                'aka_list'              => '[]'
            ]
        ];
        
        $this->assertEquals($expected, $this->importer->data);        
    }
    
    public function testAkaList()
    {
        $this->importer->data = [
            ["Brady","Kristen","","Beady","Kristen","Brady","Brady","Kristin","","","","CRMA","Excluded","2015/06/18"],
            ["Backman","Stacey","","Marzakis","Stacey","Backmann","Stacey","Backman","Stacy","","","Other","Excluded","2015/07/20"]
        ];
    
        $this->importer->preProcess();
    
        $expected = [
            [
                [
                    'last_name'     => 'Beady',
                    'first_name'    => 'Kristen'
                ]
                ,[
                    'last_name'     => 'Brady',
                    'first_name'    => 'Brady'
                ]
                ,[
                    'last_name'     => 'Kristin',
                    'first_name'    => ''
                ]
            ]
            ,[
                [
                    'last_name'     => 'Marzakis',
                    'first_name'    => 'Stacey'
                ]
                ,[
                    'last_name'     => 'Backmann',
                    'first_name'    => 'Stacey'
                ]
                ,[
                    'last_name'     => 'Backman',
                    'first_name'    => 'Stacy'
                ]
            ]
        ];
    
        $this->assertEquals(json_encode($expected[0]), $this->importer->data[0]['aka_list']);
        $this->assertEquals(json_encode($expected[1]), $this->importer->data[1]['aka_list']);
    }
}
