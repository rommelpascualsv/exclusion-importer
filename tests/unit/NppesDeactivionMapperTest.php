<?php

use App\Mappers\Nppes\DeactivationMapper;

class NppesDeactivationMapperTest extends \CDM\Test\TestCase
{
    public function test_nppes_deactivation_row_maps_to_database_model()
    {
        $mapper = $this->app->make(DeactivationMapper::class);

        $row = ArrayFactory::create('NppesDeactivationRow');
        $actual = $mapper->map($row);

        $expected = ArrayFactory::create('NppesDeactivationRecord');

        $this->assertEquals($expected, $actual);
    }
}
