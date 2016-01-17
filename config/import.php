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
    'hi1' => [
        'class' => 'Hawaii',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'ky1' => [
        'class' => 'Kentucky',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'ma1' => [
        'class' => 'Massachusetts',
        'retriever' => 'csv',
        'reader' => 'csv'
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
    'njcdr' => [
        'class' => 'NewJersey',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'nyomig' => [
        'class' => 'NewYork',
        'retriever' => 'csv',
        'reader' => 'csv'
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
    'mo1' => [
        'class'		=>	'Missouri',
        'retriever'	=>	'csv',
        'reader'	=>	'csv'
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
    'ks1' => [
        'class' => 'Kansas',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'la1' => [
        'class' => 'Louisiana',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'mt1' => [
        'class' => 'Montana',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'wv2' => [
        'class' => 'WestVirginia',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'ia1' => [
        'class' => 'Iowa',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'ga1' => [
        'class' => 'Georgia',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'me1' => [
        'class' => 'Maine',
        'retriever' => 'csv',
        'reader' => 'csv'
    ]
];