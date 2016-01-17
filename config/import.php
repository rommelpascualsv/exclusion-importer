<?php

return [
    'al1' => [
        'class' => 'Alabama',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'ar1' => [
        'dbPrefix' => 'ar1',
        'type' => 'html',
        'uri' => 'https://ardhs.sharepointsite.net/ExcludedProvidersList/Excluded%20Provider%20List.html',
        'requestOptions' => [
            'verify'  => false,
            'headers' => [
                'Content-Type' => 'text/html'
            ]
        ],
        'fieldNames' => [
            'Division',
            'FacilityName',
            'ProviderName',
            'City',
            'State',
            'Zip'
        ],
        'hashColumns' => [],
        'dateColumns' => [],
        'retrieveOptions' => [
            'htmlFilterElement' => 'table',
            'rowElement'        => 'tr',
            'columnElement'     => 'td',
            'headerRow'         => 1,
            'offset'            => 2
        ],
    ],
    'az1' => [
        'class' => 'Arizona',
        'retrieverType' => 'csv',
        'reader' => 'csv',
        'dbPrefix' => 'az1',
        'uri' => 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/arizona/azlist.xlsx',
        'retrieveOptions' => [
            'headerRow' => 0,
            'offset' => 1
        ],
        'fieldNames' => [
            'first_name',
            'middle',
            'last_name_company_name',
            'term_date',
            'specialty',
            'npi_number'
        ]
    ],
    'ak1' => [
        'type' => 'csv',
        'dbPrefix' => 'ak1',
        'uri' => 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/alaska/aklist.xlsx',
        'fieldNames' => [
            'exclusion_date',
            'last_name',
            'first_name',
            'middle_name',
            'provider_type',
            'exclusion_authority',
            'exclusion_reason'
        ],
        'hashColumns' => [
            'exclusion_date',
            'last_name',
            'first_name',
            'middle_name',
            'exclusion_authority',
        ],
        'dateColumns' => [
            'exclusion_date' => 0
        ],
        'retrieveOptions' => [
            'headerRow' => 0,
            'offset' => 1
        ],
    ],
    'ca1' => [
        'dbPrefix' => 'ca1',
        'type' => 'csv',
        'uri' => 'https://files.medi-cal.ca.gov/pubsdoco/Publications/masters-MTP/zOnlineOnly/susp100-49_z03/suspall_092015.xls',
        'fieldNames' => [
            'last_name',
            'first_name',
            'middle_name',
            'aka_dba',
            'addresses',
            'provider_type',
            'license_numbers',
            'provider_numbers',
            'date_of_suspension',
            'active_period'
        ],
        'hashColumns' => [],
        'dateColumns' => [],
        'retrieveOptions' => [],
    ],
    'ct1' => [
        'class' => 'Connecticut',
        'retriever' => 'html',
        'reader' => 'csv'
    ],
    'fdac' => [
        'dbPrefix' => 'fdac',
        'type' => 'csv',
        'uri' => 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/fda-clinical-investigators/FDA-Clinical+Investigators+-+Disqualification+Proceedings_110920150.csv',
        'fieldNames' => [
            'name',
            'center',
            'city',
            'state',
            'status',
            'date_of_status',
            'date_of_nidpoe_issued',
            'date_of_nooh_issued',
        ],
        'hashColumns' => [
            'name',
            'center',
            'status',
            'date_of_status',
        ],
        'dateColumns' => [
            "date_of_status" => 5,
            "date_nidpoe_issued" => 6,
            "date_nooh_issued" => 7
        ],
        'retrieveOptions' => [
            'headRow' => 0,
            'offset' => 1
        ],
    ],
    'fdadl' => [
        'dbPrefix' => 'fdadl',
        'type' => 'csv',
        'uri' => 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/fdadl/FDA+Debarment+List+(Drug+Product+Applications).csv',
        'fieldNames' => [
            'name',
            'aka',
            'effective_date',
            'term_of_debarment',
            'from_date',
        ],
        'hashColumns' => [
            'name',
            'aka',
            'effective_date',
            'term_of_debarment'
        ],
        'dateColumns' => [
            'effective_date' => 2,
            'from_date' => 4,
        ],
        'retrieveOptions' => [
            'headRow' => 0,
            'offset' => 1
        ],
    ],
    'fl1' => [
        'dbPrefix' => 'fl1',
        'type' => 'csv',
        'uri' => 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/florida/FOReport.xls',
        'fieldNames' => [
            'provider',
            'medicaid_provider_number',
            'license_number',
            'npi_number',
            'provider_type',
            'date_rendered',
            'sanction_type',
            'violation_code',
            'fine_amount',
            'sanction_date',
            'ahca_case_number',
            'formal_informal_case_number',
            'document_type'
        ],
        'hashColumns' => [
            'provider',
            'medicaid_provider_number',
            'license_number',
            'npi_number',
            'date_rendered',
            'sanction_date'
        ],
        'dateColumns' => [
            'date_rendered' => 5,
            'sanction_date' => 9
        ],
        'retrieveOptions' => [
            'headerRow' => 0,
            'offset' => 1
        ],
        'shouldHashListName' => true
    ],
    'ga1' => [
        'dbPrefix' => 'ga1',
        'type' => 'csv',
        'uri' => 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/georgia/Georgia.xlsx',
        'fieldNames' => [
            'last_name',
            'first_name',
            'business_name',
            'general',
            'state',
            'sanction_date'
        ],
        'hashColumns' => [
            'last_name',
            'first_name',
            'business_name',
            'sanction_date'
        ],
        'dateColumns' => [
            'sanction_date' => 5
        ],
        'retrieveOptions' => [
            'headerRow' => 0,
            'offset' => 1
        ],
    ],
    'hi1' => [
        'class' => 'Hawaii',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'ia1' => [
        'dbPrefix' => 'ia1',
        'type' => 'csv',
        'uri' => 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/iowa/ia.csv',
        'fieldNames' => [
            'sanction_start_date',
            'npi',
            'individual_last_name',
            'individual_first_name',
            'entity_name',
            'sanction',
            'sanction_end_date'
        ],
        'hashColumns' => [
            'sanction_start_date',
            'npi',
            'individual_last_name',
            'individual_first_name',
            'entity_name',
            'sanction_end_date'
        ],
        'dateColumns' => [
            'sanction_start_date' => 0,
            'sanction_end_date' => 6
        ],
        'retrieveOptions' => [
            'headerRow' => 0,
            'offset' => 1
        ],
    ],
    'ks1' => [
        'dbPrefix' => 'ks1',
        'type' => 'csv',
        'uri' => 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/kansas/ks.csv',
        'fieldNames' => [
            'termination_date',
            'name',
            'd_b_a',
            'provider_type',
            'kmap_provider_number',
            'npi',
            'comments'
        ],
        'hashColumns' => [
            'termination_date',
            'name',
            'd_b_a',
            'npi',
        ],
        'dateColumns' => [
            'termination_date' => 0
        ],
        'retrieveOptions' => [
            'headerRow' => 0,
            'offset' => 1
        ],
    ],
    'ky1' => [
        'class' => 'Kentucky',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'la1' => [
        'dbPrefix' => 'la1',
        'type' => 'csv',
        'uri' => 'https://adverseactions.dhh.la.gov/SelSearch/GetCsv',
        'fieldNames' => [
            'first_name',
            'last_or_entity_name',
            'birthdate',
            'affiliated_entity',
            'title_or_type',
            'npi',
            'exclusion_reason',
            'period_of_exclusion',
            'effective_date',
            'reinstate_date',
            'state_zip'
        ],
        'hashColumns' => [
            'first_name',
            'last_or_entity_name',
            'birthdate',
            'affiliated_entity',
            'npi',
            'exclusion_reason',
            'effective_date',
        ],
        'dateColumns' => [
            'birthdate' => 2,
            'effective_date' => 8,
        ],
        'retrieveOptions' => [
            'headerRow' => 0,
            'offset' => 2
        ],
    ],
    'ma1' => [
        'dbPrefix' => 'ma1',
        'type' => 'csv',
        'uri' => 'http://www.mass.gov/eohhs/docs/masshealth/provlibrary/suspended-excluded-masshealth-providers.xls',
        'fieldNames' => [
            'provider_name',
            'provider_type',
            'npi',
            'reason',
            'effective_date'
        ],
        'hashColumns' => [],
        'dateColumns' => [],
        'retrieveOptions' => [
            'headerRow' => 0,
            'offset' => 1
        ],
    ],
    'md1' => [
        'class' => 'Maryland',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'mi1' => [
        'class' => 'Michigan',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'mo1' => [
        'dbPrefix' => 'mo1',
        'type' => 'csv',
        'uri' => '',
        'fieldNames' => [
            'termination_date',
            'letter_date',
            'provider_name',
            'npi',
            'provider_type',
            'license_number',
            'termination_reason'
        ],
        'hashColumns' => [
            'provider_name',
            'termination_date',
            'npi'
        ],
        'dateColumns' => [
            'termination_date' => 0,
            'letter_date' => 1
        ],
        'retrieveOptions' => [],
    ],
    'mt1' => [
        'dbPrefix' => 'mt1',
        'type' => 'csv',
        'uri' => 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/montana/mt.xlsx',
        'fieldNames' => [
            'provider_name',
            'provider_type',
            'exclusion_termination_date',
            'exclusion_termination_agency',
        ],
        'hashColumns' => [
            'provider_name',
            'provider_type',
            'exclusion_termination_date'
        ],
        'dateColumns' => [
            'exclusion_termination_date' => 2,
        ],
        'retrieveOptions' => [
            'headerRow' => 0,
            'offset' => 1
        ],
    ],
    'njcdr' => [
        'dbPrefix' => 'njcdr',
        'type' => 'csv',
        'uri' => 'http://www.state.nj.us/treasury/debarment/files/Debarment.txt',
        'fieldNames' => [
            'firm_name',
            'name',
            'vendor_id',
            'firm_street',
            'firm_city',
            'firm_state',
            'firm_zip',
            'firm_plus4',
            'npi',
            'street',
            'city',
            'state',
            'zip',
            'plus4',
            'category',
            'action',
            'reason',
            'debarring_dept',
            'debarring_agency',
            'effective_date',
            'expiration_date',
            'permanent_debarment'
        ],
        'hashColumns' => [
            'firm_name',
            'name',
            'npi',
            'effective_date',
            'expiration_date',
            'permanent_debarment'
        ],
        'dateColumns' => [
            'effective_date'  => 19,
            'expiration_date' => 20
        ],
        'retrieveOptions' => [
            'headerRow' => 0,
            'offset'    => 0
        ],
        'shouldHashListName' => true,
    ],
    'nyomig' => [
        'dbPrefix' => 'nyomig',
        'type' => 'csv',
        'uri' => 'http://www.omig.ny.gov/data/gensplistns.php',
        'fieldNames' => [
            'business',
            'provider_number',
            'npi',
            'provtype',
            'action_date',
            'action_type'
        ],
        'hashColumns' => [
            'business',
            'provider_number',
            'npi',
            'action_date'
        ],
        'dateColumns' => [],
        'retrieveOptions' => [
            'headerRow' => 0,
            'offset' => 1
        ],
    ],
    'oh1' => [
        'class' => 'Ohio',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'pa1' => [
        'class' => 'Pennsylvania',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'sc1' => [
        'class' => 'SouthCarolina',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'dc1' => [
        'class' => 'WashingtonDC',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'wa1' => [
        'class' => 'Washington',
        'retriever' => 'pdf',
        'reader' => 'csv'
    ],
    'ms1' => [
        'class' => 'Mississippi',
        'retriever' => 'pdf',
        'reader' => 'csv'
    ],
    'nd1' => [
        'class' => 'NorthDakota',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'nc1' => [
        'class' => 'NorthCarolina',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'wy1' => [
        'class' => 'Wyoming',
        'retriever' => 'pdf',
        'reader' => 'csv'
    ],
    'wv2' => [
        'class' => 'WestVirginia',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'me1' => [
        'class' => 'Maine',
        'retriever' => 'csv',
        'reader' => 'csv'
    ]
];