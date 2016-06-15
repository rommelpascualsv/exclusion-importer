<?php namespace Test\Unit;

use App\Import\Lists\Mississippi;

class MississippiTest extends \Codeception\TestCase\Test
{
    private $importer;

    /**
     * Instantiate Arizona Importer
     */
    protected function _before()
    {
        $this->importer = new Mississippi();
    }

    public function testHeaderRowsShouldNotBeIncludedInData()
    {
        $importer = $this->importer;

        $importer->data = [
            [
                '',
                'Provider Name',
                'Provider Address',
                'NPI',
                'Prov Type/Specialty',
                'Termination Effective Date',
                'Exclusion Period',
                'Termination Reason',
                'Sanction Type'
            ],
            [
                '1',
                'A& B Medical Supplies',
                '444 Gracie Lane Moscow Mills, MO 63362',
                '1427126242',
                'DME',
                '2014/05/15',
                'May 15, 2014 - Indefinite',
                'Failure to disclose all ownership and managing employees.',
                'ME'
            ]
        ];

        $importer->preProcess();

        $expected = [
            [
                'last_name' => '',
                'first_name' => '',
                'middle_name' => '',
                'entity_name' => 'A& B Medical Supplies',
                'dba' => '',
                'address' => '444 Gracie Lane Moscow Mills, MO 63362',
                'address_2' => '',
                'specialty' => 'DME',
                'exclusion_from_date' => '2014-05-15',
                'exclusion_to_date' => 'Indefinite',
                'npi' => '1427126242',
                'termination_reason' => 'Failure to disclose all ownership and managing employees.',
                'sanction_type' => 'Medicare Exclusion'
            ]
        ];

        $this->assertEquals($expected, $importer->data);
    }

    public function testMultipleAddressLinesShouldBeParsedAsSeparateColumns()
    {
        $importer = $this->importer;

        $importer->data = [
            [
                '',
                'Provider Name',
                'Provider Address',
                'NPI',
                'Prov Type/Specialty',
                'Termination Effective Date',
                'Exclusion Period',
                'Termination Reason',
                'Sanction Type'
            ],
            [
                '1',
                'A& B Medical Supplies',
                '444 Gracie Lane                           Moscow Mills, MO 63362',
                '1427126242',
                'DME',
                '2014/05/15',
                'May 15, 2014 - Indefinite',
                'Failure to disclose all ownership and managing employees.',
                'ME'
            ]
        ];

        $importer->preProcess();

        $expected = [
            [
                'last_name' => '',
                'first_name' => '',
                'middle_name' => '',
                'entity_name' => 'A& B Medical Supplies',
                'dba' => '',
                'address' => '444 Gracie Lane',
                'address_2' => 'Moscow Mills, MO 63362',
                'specialty' => 'DME',
                'exclusion_from_date' => '2014-05-15',
                'exclusion_to_date' => 'Indefinite',
                'npi' => '1427126242',
                'termination_reason' => 'Failure to disclose all ownership and managing employees.',
                'sanction_type' => 'Medicare Exclusion'
            ]
        ];

        $this->assertEquals($expected, $importer->data);
    }


    public function testDBAShouldBeRemovedFromEntityNameAndSavedAsAnotherColumn()
    {
        $importer = $this->importer;

        $importer->data = [
            [
                '12',
                'Collins and Collins, LLC dba CCI Professional Healthcare Hospice',
                '316 Central Avenue                   Laurel, MS 39441',
                '1033155510',
                'Hospice',
                '2012/10/05',
                'October 5, 2012 - October 5, 2022',
                'Owner or managing agent excluded by OIG',
                'OIG'
            ]
        ];

        $importer->preProcess();

        $expected = [
            [
                'last_name' => '',
                'first_name' => '',
                'middle_name' => '',
                'entity_name' => 'Collins and Collins, LLC',
                'dba' => 'CCI Professional Healthcare Hospice',
                'address' => '316 Central Avenue',
                'address_2' => 'Laurel, MS 39441',
                'specialty' => 'Hospice',
                'exclusion_from_date' => '2012-10-05',
                'exclusion_to_date' => 'October 5, 2022',
                'npi' => '1033155510',
                'termination_reason' => 'Owner or managing agent excluded by OIG',
                'sanction_type' => 'OIG Exclusion'
            ]
        ];

        $this->assertEquals($expected, $importer->data);
    }

    public function testFirstMiddleAndLastNamesShouldBeParsedIntoSeparateColumns()
    {
        $importer = $this->importer;

        $importer->data = [
            [
                '3',
                'Alexander, Lon Nate F.',
                '1860 Chadwick Drive, STE 205                    Jackson, MS 39204',
                '1073595989',
                'Neurological Surgery',
                '2014/03/12',
                'March 12, 2014 - January 15, 2015',
                'Prohibited from practicing medicine by MS State Board of Medical Licensure.',
                'LB'
            ]
        ];

        $importer->preProcess();

        $expected = [
            [
                'last_name' => 'Alexander',
                'first_name' => 'Lon',
                'middle_name' => 'Nate F.',
                'entity_name' => '',
                'dba' => '',
                'address' => '1860 Chadwick Drive, STE 205',
                'address_2' => 'Jackson, MS 39204',
                'specialty' => 'Neurological Surgery',
                'exclusion_from_date' => '2014-03-12',
                'exclusion_to_date' => 'January 15, 2015',
                'npi' => '1073595989',
                'termination_reason' => 'Prohibited from practicing medicine by MS State Board of Medical Licensure.',
                'sanction_type' => 'Licensing Board'
            ]
        ];

        $this->assertEquals($expected, $importer->data);
    }

    public function testFirstMiddleLastAndEntityNamesShouldBeParsedIntoSeparateColumns()
    {
        $importer = $this->importer;

        $importer->data = [
            [
                '32',
                'James, Dana Michelle               Central MS Correctional Facility',
                'P O Box 88550                                  Pearl, MS 39208',
                '',
                'Nurse',
                '2005/02/07',
                'February 7, 2005 - February 7, 2010',
                'License Revoked',
                'LB'
            ]
        ];

        $importer->preProcess();

        $expected = [
            [
                'last_name' => 'James',
                'first_name' => 'Dana',
                'middle_name' => 'Michelle',
                'entity_name' => 'Central MS Correctional Facility',
                'dba' => '',
                'address' => 'P O Box 88550',
                'address_2' => 'Pearl, MS 39208',
                'specialty' => 'Nurse',
                'exclusion_from_date' => '2005-02-07',
                'exclusion_to_date' => 'February 7, 2010',
                'npi' => '',
                'termination_reason' => 'License Revoked',
                'sanction_type' => 'Licensing Board'
            ]
        ];

        $this->assertEquals($expected, $importer->data);
    }

    public function testOwnerNameShouldBeParsedIntoFirstMiddleAndLastNameColumns()
    {
        $importer = $this->importer;

        $importer->data = [
            [
                '36',
                'Longwind Products & Services       Lonnie Walker - Owner',
                '205 West Woodrow Wilson               Jackson, MS 39213',
                '1407889934',
                'DME',
                '2012/08/08',
                'August 8, 2012 - Indefinite',
                'Owner, Lonnie Walker, sentenced to 15 months in US Bureau of Prisons',
                'F'
            ],
            [
                '36',
                'Longwind Products & Services       Lonnie X. Walker - Owner',
                '205 West Woodrow Wilson               Jackson, MS 39213',
                '1407889934',
                'DME',
                '2012/08/08',
                'August 8, 2012 - Indefinite',
                'Owner, Lonnie Walker, sentenced to 15 months in US Bureau of Prisons',
                'F'
            ]
        ];

        $importer->preProcess();

        $expected = [
            [
                'last_name' => 'Walker',
                'first_name' => 'Lonnie',
                'middle_name' => '',
                'entity_name' => 'Longwind Products & Services',
                'dba' => '',
                'address' => '205 West Woodrow Wilson',
                'address_2' => 'Jackson, MS 39213',
                'specialty' => 'DME',
                'exclusion_from_date' => '2012-08-08',
                'exclusion_to_date' => 'Indefinite',
                'npi' => '1407889934',
                'termination_reason' => 'Owner, Lonnie Walker, sentenced to 15 months in US Bureau of Prisons',
                'sanction_type' => 'Individual or Entity Convicted of Fraud'
            ],
            [
                'last_name' => 'Walker',
                'first_name' => 'Lonnie',
                'middle_name' => 'X.',
                'entity_name' => 'Longwind Products & Services',
                'dba' => '',
                'address' => '205 West Woodrow Wilson',
                'address_2' => 'Jackson, MS 39213',
                'specialty' => 'DME',
                'exclusion_from_date' => '2012-08-08',
                'exclusion_to_date' => 'Indefinite',
                'npi' => '1407889934',
                'termination_reason' => 'Owner, Lonnie Walker, sentenced to 15 months in US Bureau of Prisons',
                'sanction_type' => 'Individual or Entity Convicted of Fraud'
            ]
        ];

        $this->assertEquals($expected, $importer->data);
    }

    public function testMultipleNPIsShouldBeParsedCorrectly()
    {
        $importer = $this->importer;

        $importer->data = [
            [
                '3',
                'Alexander, Lon F.',
                '1860 Chadwick Drive, STE 205                    Jackson, MS 39204',
                '1073595989, 1346485794',
                'Neurological Surgery',
                '2014/03/12',
                'March 12, 2014 - January 15, 2015',
                'Prohibited from practicing medicine by MS State Board of Medical Licensure.',
                'LB'
            ]
        ];

        $importer->preProcess();

        $expected = [
            [
                'last_name' => 'Alexander',
                'first_name' => 'Lon',
                'middle_name' => 'F.',
                'entity_name' => '',
                'dba' => '',
                'address' => '1860 Chadwick Drive, STE 205',
                'address_2' => 'Jackson, MS 39204',
                'specialty' => 'Neurological Surgery',
                'exclusion_from_date' => '2014-03-12',
                'exclusion_to_date' => 'January 15, 2015',
                'npi' => '["1073595989","1346485794"]',
                'termination_reason' => 'Prohibited from practicing medicine by MS State Board of Medical Licensure.',
                'sanction_type' => 'Licensing Board'
            ]
        ];

        $this->assertEquals($expected, $importer->data);
    }

    public function testUnknownSanctionTypeShouldBeParsedAsIs()
    {
        $importer = $this->importer;

        $importer->data = [
            [
                '3',
                'Alexander, Lon F.',
                '1860 Chadwick Drive, STE 205                    Jackson, MS 39204',
                '1073595989, 1346485794',
                'Neurological Surgery',
                '2014/03/12',
                'March 12, 2014 - January 15, 2015',
                'Prohibited from practicing medicine by MS State Board of Medical Licensure.',
                'XX'
            ]
        ];

        $importer->preProcess();

        $expected = [
            [
                'last_name' => 'Alexander',
                'first_name' => 'Lon',
                'middle_name' => 'F.',
                'entity_name' => '',
                'dba' => '',
                'address' => '1860 Chadwick Drive, STE 205',
                'address_2' => 'Jackson, MS 39204',
                'specialty' => 'Neurological Surgery',
                'exclusion_from_date' => '2014-03-12',
                'exclusion_to_date' => 'January 15, 2015',
                'npi' => '["1073595989","1346485794"]',
                'termination_reason' => 'Prohibited from practicing medicine by MS State Board of Medical Licensure.',
                'sanction_type' => 'XX'
            ]
        ];

        $this->assertEquals($expected, $importer->data);
    }
}