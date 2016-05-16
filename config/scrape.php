<?php

return [
    
    /*
     |--------------------------------------------------------------------------
     | User Agent
     |--------------------------------------------------------------------------
     |
     | This is the user agent that is used by Goutte\Client when resolved by the
     | container
     |
     */
    
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36',
    
    /*
     |--------------------------------------------------------------------------
     | Import Connecticut Category File
     |--------------------------------------------------------------------------
     |
     | The json file which contains data of all the fields in
     | https://www.elicense.ct.gov/Lookup/GenerateRoster.aspx
     |
     */
    
    'import' => [
        'connecticut_categories' => base_path('resources/scrape/connecticut-categories.json')
    ],
    
    /*
     |--------------------------------------------------------------------------
     | Connecticut Categories
     |--------------------------------------------------------------------------
     |
     | The categories that will be downloaded when calling the 
     | scrape_connecticut:download_csv command
     |
     */
    
    'connecticut_categories' => [
        'ambulatory_surgical_centers_recovery_care_centers',
        'controlled_substances_practitioners_labs_manufacturers',
        'child_day_care_licensing_program',
        'drug_control_pharmacy_pharmacists_etc',
        'emergency_medical_services',
        'healthcare_practitioners',
        'hemodialysis_centers',
        'hospitals',
        'infirmaries_clinics',
        'long_term_care_assisted_living_facilities_home_health_care_hospice',
        'medical_marijuana_producer_dispensary_facility_dispensary_etc',
        'mental_health_care',
        'registered_sanitarians',
        'substance_abuse_care'
    ]
    
];