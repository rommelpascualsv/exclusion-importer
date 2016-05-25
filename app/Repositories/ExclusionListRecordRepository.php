<?php

namespace App\Repositories;

/**
 * Repository for exclusion list records
 *
 */
class ExclusionListRecordRepository implements Repository
{
    public function create($record)
    {
        throw new Exception("This operation is not supported");
    }
    
    public function clear()
    {
        throw new Exception("This operation is not supported");
    }
    
    public function find($id)
    {
        throw new Exception("This operation is not supported");    
    }
    
    /**
     * Returns the current number of records in the '<prefix>_records' table
     * @param string $prefix the prefix of the exclusion list whose record count
     * should be returned
     */
    public function size($prefix)
    {
        return app('db')->table($prefix . '_records')->count();
    }    
}
