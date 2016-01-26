<?php namespace App\Import\Lists;


class NewYork extends ExclusionList
{

	public $dbPrefix = 'nyomig';

	public $uri = 'http://www.omig.ny.gov/data/gensplistns.php';

	public $type = 'txt';

	public $retrieveOptions = [
		'headerRow' => 0,
		'offset' => 1
	];

	public $fieldNames = [
		'business',
		'provider_number',
		'npi',
		'provtype',
		'action_date',
		'action_type'
	];

	public $hashColumns = [
		'business',
		'provider_number',
		'npi',
		'action_date'
	];
}
