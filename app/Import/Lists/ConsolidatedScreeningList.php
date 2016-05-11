<?php namespace App\Import\Lists;

class ConsolidatedScreeningList extends ExclusionList
{
    /**
     * @var string
     */
    public $dbPrefix = 'csl';

    /**
     * @var string
     */
    public $uri = 'https://api.trade.gov/consolidated_screening_list/search.csv';

    /**
     * @var string
     */
    public $type = 'csv';

    /**
     * @var array
     */
    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    /**
     * @var array
     */
    public $fieldNames = [
        'source',
        'entity_number',
        'type',
        'programs',
        'name',
        'title',
        'addresses',
        'federal_register_notice',
        'start_date',
        'end_date',
        'standard_order',
        'license_requirement',
        'license_policy',
        'call_sign',
        'vessel_type',
        'gross_tonnage',
        'gross_registered_tonnage',
        'vessel_flag',
        'vessel_owner',
        'remarks',
        'source_list_url',
        'alt_names',
        'citizenships',
        'dates_of_birth',
        'nationalities',
        'places_of_birth',
        'source_information_url'
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'source',
        'entity_number',
        'type',
        'programs',
        'name',
        'title',
        'addresses',
        'federal_register_notice',
        'start_date',
        'end_date',
        'standard_order',
        'license_requirement',
        'license_policy',
        'call_sign',
        'vessel_type',
        'gross_tonnage',
        'gross_registered_tonnage',
        'vessel_flag',
        'vessel_owner',
        'remarks',
        'source_list_url',
        'alt_names',
        'citizenships',
        'dates_of_birth',
        'nationalities',
        'places_of_birth',
        'source_information_url'
    ];

    /**
     * @var array
     */
    public $dateColumns = [
        'start_date' => 8,
        'end_date' => 9
    ];

    public $shouldHashListName = true;
}
