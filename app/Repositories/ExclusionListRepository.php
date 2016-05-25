<?php

namespace App\Repositories;

/**
 * Repository for exclusion lists
 */
class ExclusionListRepository implements Repository
{
    
    public function create($record)
    {
        app('db')->table('exclusion_lists')->create($record);    
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
     * Retrieves all exclusion lists that satisfy the given criteria. If a $criteria
     * is specified, then the returned rows are filtered by the parameters
     * set in the criteria, otherwise returns all exclusion lists
     * 
     * @param array filter (optional) array of key-value pairs with which to filter 
     * the exclusion list data
     * @return array
     */
    public function query($criteria = null)
    {
        $query = app('db')->table('exclusion_lists');
        
        if ($criteria) {
            $query->where($criteria);
        }

        return $query->get();
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
    }
}
