<?php

use App\Import\Lists\Alabama;

class AlabamaTest extends \Codeception\TestCase\Test
{
    private $importer;
    
    protected function _before()
    {
        $this->importer = new Alabama();
    }
    
    public function testHeaderRowsShouldNotBeIncludedInData()
    {
        $importer = $this->importer;
        
        $importer->data = [
             ['NAME OF PROVIDER','SUSPENSION','SUSPENSION'],
             ['','EFFECTIVE DATE','INITIATED BY'],
             ['PHYSICIANS','',''],
             ['Abell, John B.','2013/02/23','MLC']
        ];
        
        $importer->setHeaderOffset(2);
        $importer->preProcess();
        
        $expected = [
            [
                'name_of_provider'          => 'Abell, John B.',
                'suspension_effective_date' => '2013-02-23',
                'suspension_initiated_by'   => 'MLC',
                'title'                     => '',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ]
        ];
    
        $this->assertEquals($expected, $importer->data);
    }
    
    public function testFooterRowsShouldNotBeIncludedInData()
    {
        $importer = $this->importer;
        
        $importer->data = [
            ['PHYSICIANS','',''],
            ['Abell, John B.','2013/02/23','MLC'],
            ['I am the footer text','some other footer text',''],
            ['Lorem ipsum','dolor sit amet','']
        ];
    
        $importer->setHeaderOffset(0);
        $importer->setFooterIndicatorText('I am the footer text');
        $importer->preProcess();
    
        $expected = [
            [
                'name_of_provider'          => 'Abell, John B.',
                'suspension_effective_date' => '2013-02-23',
                'suspension_initiated_by'   => 'MLC',
                'title'                     => '',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ]
        ];
    
        $this->assertEquals($expected, $importer->data);
    }   
    
    public function testProviderTypesShouldBeParsedCorrectly()
    {
        $importer = $this->importer;
    
        $importer->data = [
            ['PHYSICIANS','',''],//'PHYSICIANS' provider type
            ['Abell, John B.','2013/02/23','MLC'],
            ['Aggarwal, Shelinder','2013/09/13','MLC & Medicare'],
            ['DENTISTS/DENTAL PROVIDERS','',''], //DENTISTS/DENTAL PROVIDERS provider type
            ['Campbell, Larry W.','1997/07/02','Medicare'],
        ];
    
        $importer->setHeaderOffset(0);
        $importer->preProcess();
    
        $expected = [
            [
                'name_of_provider'          => 'Abell, John B.',
                'suspension_effective_date' => '2013-02-23',
                'suspension_initiated_by'   => 'MLC',
                'title'                     => '',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Aggarwal, Shelinder',
                'suspension_effective_date' => '2013-09-13',
                'suspension_initiated_by'   => 'MLC & Medicare',
                'title'                     => '',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Campbell, Larry W.',
                'suspension_effective_date' => '1997-07-02',
                'suspension_initiated_by'   => 'Medicare',
                'title'                     => '',
                'aka_name'                  => '',
                'provider_type'             => 'DENTISTS/DENTAL PROVIDERS'
            ]            
        ];
    
        $this->assertEquals($expected, $importer->data);
    } 
    
    public function testAliasesShouldBeRemovedFromProviderNameAndStoredAsASeparateColumn()
    {
        $importer = $this->importer;
    
        $importer->data = [
            ['PHYSICIANS','',''],
            ['Johnson, Carla Maria (aka Carla Maria Johnson-Moye)','1998/08/12','MLC'],
            ['Hawkins, Christie L. (aka Christie Y. Hawkins, Christie Lee Huerta, Christie L. Johnson, & Christie L. Smith )','2007/11/20','Medicare'],
            ['Beverly, LeAnn Rene Anderson a.k.a. Leann Beverly','2014/10/20','Medicare']
        ];
    
        $importer->setHeaderOffset(0);
        $importer->preProcess();
    
        $expected = [
            [
                'name_of_provider'          => 'Johnson, Carla Maria',
                'suspension_effective_date' => '1998-08-12',
                'suspension_initiated_by'   => 'MLC',
                'title'                     => '',
                'aka_name'                  => 'Carla Maria Johnson-Moye',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Hawkins, Christie L.',
                'suspension_effective_date' => '2007-11-20',
                'suspension_initiated_by'   => 'Medicare',
                'title'                     => '',
                'aka_name'                  => 'Christie Y. Hawkins, Christie Lee Huerta, Christie L. Johnson, & Christie L. Smith',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Beverly, LeAnn Rene Anderson',
                'suspension_effective_date' => '2014-10-20',
                'suspension_initiated_by'   => 'Medicare',
                'title'                     => '',
                'aka_name'                  => 'Leann Beverly',
                'provider_type'             => 'PHYSICIANS'
            ],
            
        ];
    
        $this->assertEquals($expected, $importer->data);
    } 
    
    public function testTitlesShouldBeRemovedFromNamesOfIndividualsAndStoredInASeparateColumn()
    {
        $importer = $this->importer;
    
        $importer->data = [
            ['PHYSICIANS','',''],
            //No name suffix
            ['Alexander, Eloise Karin Lundberg, MD','2012/03/21','MLC'],
            //With name suffix (i.e. Jr)
            ['Benson, James Dawson, Jr., RN','1999/09/20','Medicare'],
            //With alias (aka version)
            ['Broom, Marla Beth, RN (aka Marla Beth Painter & Marla Beth Anderson)','2015/03/19','Medicare'],
            //With alias (a.k.a. version)
            ['Beverly, LeAnn Rene Anderson, MD a.k.a. Leann Beverly','2014/10/20','Medicare'],
            //Special case - 'Owner of Hospice Facility'
            ['Gist, Jackie Randolph, Owner of Hospice Facility','2012/07/19','Medicare'],
            //Special case - 'Owner of Hospice Facility'
            ['Gist, Jackie Randolph, Jr., Owner of Hospice Facility','2012/07/19','Medicare'],
            //Enclosed in parentheses
            ['Arias, Exlan, (DME Owner)','2008/07/20','Medicare']
        ];
    
        $importer->setHeaderOffset(0);
        $importer->preProcess();
    
        $expected = [
            [
                'name_of_provider'          => 'Alexander, Eloise Karin Lundberg',
                'suspension_effective_date' => '2012-03-21',
                'suspension_initiated_by'   => 'MLC',
                'title'                     => 'MD',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Benson, James Dawson, Jr.',
                'suspension_effective_date' => '1999-09-20',
                'suspension_initiated_by'   => 'Medicare',
                'title'                     => 'RN',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Broom, Marla Beth',
                'suspension_effective_date' => '2015-03-19',
                'suspension_initiated_by'   => 'Medicare',
                'title'                     => 'RN',
                'aka_name'                  => 'Marla Beth Painter & Marla Beth Anderson',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Beverly, LeAnn Rene Anderson',
                'suspension_effective_date' => '2014-10-20',
                'suspension_initiated_by'   => 'Medicare',
                'title'                     => 'MD',
                'aka_name'                  => 'Leann Beverly',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Gist, Jackie Randolph',
                'suspension_effective_date' => '2012-07-19',
                'suspension_initiated_by'   => 'Medicare',
                'title'                     => 'Owner of Hospice Facility',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Gist, Jackie Randolph, Jr.',
                'suspension_effective_date' => '2012-07-19',
                'suspension_initiated_by'   => 'Medicare',
                'title'                     => 'Owner of Hospice Facility',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Arias, Exlan',
                'suspension_effective_date' => '2008-07-20',
                'suspension_initiated_by'   => 'Medicare',
                'title'                     => 'DME Owner',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ]            
            
        ];
    
        $this->assertEquals($expected, $importer->data);
    }    
    
    public function testProviderNamesOfInstitutionsOrOwnersOfInstitutionsShouldBeParsedAsIs()
    {
        $importer = $this->importer;
    
        $importer->data = [
            ['PHYSICIANS','',''],
            ['Home Buddies of Northwest Alabama, Inc./Alabama Angels, Inc., Direct Service Provider (Benita Owens, Owner)','2010/09/02','Medicaid'],
            ['Owens, Benita, Owner, Home Buddies of Northwest Alabama, Inc./Alabama Angels, Inc.','2010/09/02','Medicaid'],
            ['Advantage Medical Supply (John Michael Johnson, Joan Johnson, & Marcus Johnson, Owners)','2010/03/30','Medicaid'],
            ['Johnson, Joan (Officer Manager/Owner, Advantage Medical Supply)','2010/03/30','Medicaid & MFCU'],
            ['Major Medical, Inc.','1993/08/03','Medicare'],
            ['Margulis Enterprises, Inc. dba Covenant Medical Supplies, Opelika, Alabama','2006/01/19','Medicare']
        ];
    
        $importer->setHeaderOffset(0);
        $importer->preProcess();
    
        $expected = [
            [
                'name_of_provider'          => 'Home Buddies of Northwest Alabama, Inc./Alabama Angels, Inc., Direct Service Provider (Benita Owens, Owner)',
                'suspension_effective_date' => '2010-09-02',
                'suspension_initiated_by'   => 'Medicaid',
                'title'                     => '',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Owens, Benita, Owner, Home Buddies of Northwest Alabama, Inc./Alabama Angels, Inc.',
                'suspension_effective_date' => '2010-09-02',
                'suspension_initiated_by'   => 'Medicaid',
                'title'                     => '',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Advantage Medical Supply (John Michael Johnson, Joan Johnson, & Marcus Johnson, Owners)',
                'suspension_effective_date' => '2010-03-30',
                'suspension_initiated_by'   => 'Medicaid',
                'title'                     => '',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Johnson, Joan (Officer Manager/Owner, Advantage Medical Supply)',
                'suspension_effective_date' => '2010-03-30',
                'suspension_initiated_by'   => 'Medicaid & MFCU',
                'title'                     => '',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Major Medical, Inc.',
                'suspension_effective_date' => '1993-08-03',
                'suspension_initiated_by'   => 'Medicare',
                'title'                     => '',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ],
            [
                'name_of_provider'          => 'Margulis Enterprises, Inc. dba Covenant Medical Supplies, Opelika, Alabama',
                'suspension_effective_date' => '2006-01-19',
                'suspension_initiated_by'   => 'Medicare',
                'title'                     => '',
                'aka_name'                  => '',
                'provider_type'             => 'PHYSICIANS'
            ]            
    
        ];
    
        $this->assertEquals($expected, $importer->data);
    }    
    
}