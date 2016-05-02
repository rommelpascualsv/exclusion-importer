<?php

use App\Mappers\Nppes\ProviderMapper;

class NppesMapperTest extends TestCase 
{
    public function test_nppes_row_maps_to_database_model()
    {
        $mapper = $this->app->make(ProviderMapper::class);

        $row = ArrayFactory::create('NppesRow');
        $actual = $mapper->map($row);

        $expected = ArrayFactory::create('NppesRecord');

        $this->assertEquals($expected, $actual);
    }
}
