<?php

use App\Mappers\Taxonomy\EntryMapper;

class TaxonomyMapperTest extends \CDM\Test\TestCase
{
    public function test_taxonomy_row_maps_to_database_model()
    {
        $mapper = $this->app->make(EntryMapper::class);

        $row = ArrayFactory::create('TaxonomyRow');
        $actual = $mapper->map($row);

        $expected = ArrayFactory::create('TaxonomyRecord');
        $expected['definition'] = mb_convert_encoding($expected['definition'], 'UTF-8');

        $this->assertEquals($expected, $actual);
    }
}
