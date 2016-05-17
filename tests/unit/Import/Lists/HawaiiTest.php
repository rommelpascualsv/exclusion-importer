<?php

use App\Import\Lists\Hawaii;

class HawaiiTest extends \Codeception\TestCase\Test
{
    private $importer;
    
    protected function _before()
    {
        $this->importer = new Hawaii();
    }
    
    public function testExclusionDateShouldBeEmptyIfProvidedDateIsDash()
    {
        $this->importer->data = [
            ["SMITH","JOHN","","12345","OB-GYNE","-","Indefinite"]
        ];
        
        $this->importer->preProcess();
        
        $this->assertEquals("", $this->importer->data[0]["exclusion_date"]);
    }
    
    public function testValidConversion()
    {
        $this->importer->data = [
            ["ACHAVAL","MARIA","","596009","PSYCHOLOGIST","2015/04/20","Indefinite"],
            ["SMITH","JOHN","","12345","OB-GYNE","-","Indefinite"],
            ["DOE","JANE","","67890","PEDIATRICIAN","2016/01/05","Indefinite"],
        ];
        
        $this->importer->preProcess();
        
        $expected = [
            [
                "last_name_or_business"                 => "ACHAVAL",
                "first_name"                            => "MARIA",
                "middle_initial"                        => "",
                "medicaid_provide_id_number"            => "596009",
                "last_known_program_or_provider_type"   => "PSYCHOLOGIST",
                "exclusion_date"                        => "2015/04/20",
                "reinstatement_date"                    => "Indefinite"
            ],
            [
                "last_name_or_business"                 => "SMITH",
                "first_name"                            => "JOHN",
                "middle_initial"                        => "",
                "medicaid_provide_id_number"            => "12345",
                "last_known_program_or_provider_type"   => "OB-GYNE",
                "exclusion_date"                        => "",
                "reinstatement_date"                    => "Indefinite"
            ],
            [
                "last_name_or_business"                 => "DOE",
                "first_name"                            => "JANE",
                "middle_initial"                        => "",
                "medicaid_provide_id_number"            => "67890",
                "last_known_program_or_provider_type"   => "PEDIATRICIAN",
                "exclusion_date"                        => "2016/01/05",
                "reinstatement_date"                    => "Indefinite"
            ]
        ];
        
        $this->assertEquals($expected, $this->importer->data);        
    }
}
