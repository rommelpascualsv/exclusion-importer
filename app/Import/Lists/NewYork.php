<?php namespace App\Import\Lists;


class NewYork extends ExclusionList
{

	public $dbPrefix = 'nyomig';

	public $uri = 'http://www.omig.ny.gov/data/gensplistns.php';

	public $retrieveOptions = array(
		'headerRow' => 0,
		'offset' => 1
	);

	public $fieldNames = array(
		'business',
		'provider_number',
		'npi',
		'provtype',
		'action_date',
		'action_type'
	);

	public $hashColumns = [
		'business',
		'provider_number',
		'npi',
		'action_date'
	];
}
