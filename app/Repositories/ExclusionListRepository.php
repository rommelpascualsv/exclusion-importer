<?php

namespace App\Repositories;

/**
 * Repository for exclusion lists
 */
class ExclusionListRepository implements Repository
{
    
    public function create($record)
    {
        return app('db')->table('exclusion_lists')->insert($record);    
    }
    
    public function clear()
    {
        app('db')->table('exclusion_lists')->truncate();
    }
    
    /**
     * Finds a record with the given prefix in the exclusion_lists table
     * @param string $prefix
     */
    public function find($prefix)
    {
        return app('db')->table('exclusion_lists')->where('prefix', $prefix)->get();
    }    
    
    /**
     * Updates the record with the given prefix in the exclusion_lists table
     * @param string $prefix the prefix of the exclusion list whose column values
     * will be updated
     * @param array $data the column values to update
     */
    public function update($prefix, $data) 
    {
        $result = app('db')->table('exclusion_lists')->where('prefix', $prefix)->update($data);
        info('Updated ' . $result . ' rows for ' . $prefix);
        return $result;
    }
}
