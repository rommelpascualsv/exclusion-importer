<?php namespace App\Mappers\MICna;

use App\Mappers\Mapper;

class EntryMapper extends Mapper
{
    public function map($data)
    {
        $record = [];
        $this->addProperties($record, $this->miCnaHeaders, $data);
        return $record;
    }

    private $miCnaHeaders = [
        "first_name" => "first_name",
        "last_name" => "last_name",
        "middle_name" => "middle_name",
        "address_line_1" => "addr_line_1",
        "address_line_2" => "addr_line_2",
        "address_line_3" => "addr_line_3",
        "city" => "addr_city",
        "state" => "addr_state",
        "zip" => "addr_zipcode",
        "date_of_birth" => "date_of_birth",
        "certificate_number" => "certificate_number",
        "certificate_status" => "certificate_status",
        "expiration_date" => "expiration_date"
    ];
}
