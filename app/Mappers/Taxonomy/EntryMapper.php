<?php namespace App\Mappers\Taxonomy;

use App\Mappers\Mapper;

class EntryMapper extends Mapper {

	public function map($data)
	{
		$record = [];
		$this->addProperties($record, $this->taxonomyHeaders, $data);
		return $record;
	}

	private $taxonomyHeaders = [
		"code" => "Code",
		"type" => "Type",
		"classification" => "Classification",
		"specialization" => "Specialization",
		"definition" => "Definition",
		"notes" => "Notes"
	];
}
