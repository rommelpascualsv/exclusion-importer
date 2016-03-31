<?php namespace App\Import\Lists;

class NewJersey extends ExclusionList
{
    /**
     * @var string
     */
    public $dbPrefix = 'njcdr';

    public $uri = 'http://www.state.nj.us/treasury/debarment/files/Debarment.txt';

    public $type = 'txt';

    public $hashFields = [
        'firm_name',
        'name',
        'npi',
        'effective_date',
        'expiration_date',
        'permanent_debarment'
    ];

    public $fieldNames = [
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
    ];

    /**
     * @var array
     */
    public $dateColumns = [
        'effective_date'  => 19,
        'expiration_date' => 20
    ];

    /**
     * @var array
     */
    public $retrieveOptions = [
        'headerRow' => 0,
        'offset'    => 0
    ];

    public $shouldHashListName = true;
}
