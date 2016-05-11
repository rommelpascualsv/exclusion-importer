<?php namespace App\Mappers\Njna;

use App\Mappers\Mapper;

class EntryMapper extends Mapper
{
    public function map($data)
    {
        $record = [];
        $this->addProperties($record, $this->njnaHeaders, $data);
        return $record;
    }

    private $njnaHeaders = [
        "last_name" => "Last Name",
        "first_name" => "First Name",
        "middle_name" => "Middle Name",
        "certificate_number" => "Certificate #",
        "issue_date" => "Issue Date",
        "expiration_date" => "Expiration Date",
        "certificate_status" => "Certificate Status"
    ];
}
