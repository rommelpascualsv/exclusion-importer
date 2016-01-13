<?php

return [
    'al1' => [
        'class' => 'Alabama',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'ar1' => [
        'class' => 'Arkansas',
        'retriever' => 'html',
        'reader' => 'csv'
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
        'class' => 'Alaska',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'ca1' => [
        'class' => 'California',
        'retriever' => 'csv',
        'reader' => 'csv'
    ],
    'ct1' => [
        'class' => 'Connecticut',
        'retriever' => 'html',
        'reader' => 'csv'
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