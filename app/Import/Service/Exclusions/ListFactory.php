<?php namespace App\Import\Service\Exclusions;

class ListFactory
{
    public $listMappings = [
        'al1'     => 'Alabama',
        'ar1'     => 'Arkansas',
        'az1'     => 'Arizona',
        'ak1'     => 'Alaska',
        'ca1'     => 'California',
        'ct1'     => 'Connecticut',
        'fl2'     => 'Florida',
        'hi1'     => 'Hawaii',
        'ky1'     => 'Kentucky',
        'ma1'     => 'Massachusetts',
        'md1'     => 'Maryland',
        'mi1'     => 'Michigan',
        'njcdr'   => 'NewJersey',
        'nyomig'  => 'NewYork',
        'oh1'     => 'Ohio',
        'pa1'     => 'Pennsylvania',
        'sc1'     => 'SouthCarolina',
        'dc1'     => 'WashingtonDC',
        'wa1'     => 'Washington',
        'ms1'     => 'Mississippi',
        'mo1'     => 'Missouri',
        'nd1'     => 'NorthDakota',
        'nc1'     => 'NorthCarolina',
        'wy1'     => 'Wyoming',
        'ks1'     => 'Kansas',
        'la1'     => 'Louisiana',
        'mt1'     => 'Montana',
        'wv2'     => 'WestVirginia',
        'ia1'     => 'Iowa',
        'ga1'     => 'Georgia',
        'me1'     => 'Maine',
        'tn1'     => 'Tennessee',
        'fdac'    => 'FDAClinical',
        'phs'     => 'PHS',
        'usdocdp' => 'USDOCDeniedPersons',
        'fdadl'   => 'FDADebarmentList'
    ];

    public function make($prefix) {
        if (array_key_exists($prefix, $this->listMappings)) {

            $class = "App\\Import\\Lists\\{$this->listMappings[$prefix]}";

            return new $class;
        }

        throw new \RuntimeException("Unsupported Exclusion List prefix");
    }
}