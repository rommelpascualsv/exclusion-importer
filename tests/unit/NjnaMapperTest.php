<?php

use App\Mappers\Njna\EntryMapper;

class NjnaMapperTest extends TestCase 
{
    public function test_njna_row_maps_to_database_model()
    {
        $mapper = $this->app->make(EntryMapper::class);

        $row = ArrayFactory::create('NjnaRow');
        $actual = $mapper->map($row);

        $expected = ArrayFactory::create('NjnaRecord');

        $this->assertEquals($expected, $actual);
    }
}
