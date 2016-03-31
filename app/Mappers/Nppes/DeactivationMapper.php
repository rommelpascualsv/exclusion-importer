<?php namespace App\Mappers\Nppes;

use App\Mappers\Mapper;

class DeactivationMapper extends Mapper 
{
	public function map($data)
	{
		$record = [];
		$this->addProperties($record, $this->nppesHeaders, $data);
		return $record;
	}

	private $nppesHeaders = [
		'npi' => 'NPI',
		'deactivation_date' => 'NPPES Deactivation Date',
	];
}
