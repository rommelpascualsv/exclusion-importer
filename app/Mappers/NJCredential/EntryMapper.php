<?php

namespace App\Mappers\NJCredential;

use App\Mappers\Mapper;

class EntryMapper extends Mapper 
{
	public function map($data)
	{
		$record = [];
		$this->addProperties($record, $this->njCredentialHeaders, $data);
		return $record;
	}

	private $njCredentialHeaders = [
		"profession_name" => "profession_name",
		"license_type" => "license_type",
		"full_name" => "full_name",
		"first_name" => "first_name",
		"middle_name" => "middle_name",
		"last_name" => "last_name",
		"name_suffix" => "name_suffix",
		"gender" => "gender",
		"license_number" => "license_no",
		"issue_date" => "issue_date",
		"expiration_date" => "expiration_date",
		"address_line_1" => "addr_line_1",
		"address_line_2" => "addr_line_2",
		"city" => "addr_city",
		"state" => "addr_state",
		"zip" => "addr_zipcode",
		"license_status_name" => "license_status_name"
	];
}
