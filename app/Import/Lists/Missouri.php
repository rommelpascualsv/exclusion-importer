<?php namespace App\Import\Lists;

class Missouri extends ExclusionList
{
	public $dbPrefix = 'mo1';

	public $uri = 'http://www.mmac.mo.gov/files/Sanction-List-10-15-upd.xls';

	public $type = 'xls';

	public $hashColumns = [
        'provider_name',
		'termination_date',
		'npi'
	];

	public $fieldNames = [
		'termination_date',
		'letter_date',
		'provider_name',
		'npi',
        'provider_type',
		'license_number',
		'termination_reason'
	];

	public $dateColumns = [
		'termination_date' => 0,
		'letter_date' => 1
	];
}
