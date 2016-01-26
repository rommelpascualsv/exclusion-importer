<?php namespace App\Import\Lists;


class Arkansas extends ExclusionList
{

    public $dbPrefix = 'ar1';

    public $uri = 'https://ardhs.sharepointsite.net/ExcludedProvidersList/Excluded%20Provider%20List.html';

    public $type = 'html';

    public $requestOptions = [
        'verify'  => false,
        'headers' => [
            'Content-Type' => 'text/html'
        ]
    ];

    public $retrieveOptions = array(
        'htmlFilterElement' => 'table',
        'rowElement'        => 'tr',
        'columnElement'     => 'td',
        'headerRow'         => 1,
        'offset'            => 2
    );

    public $fieldNames = array(
        'Division',
        'FacilityName',
        'ProviderName',
        'City',
        'State',
        'Zip'
    );

}