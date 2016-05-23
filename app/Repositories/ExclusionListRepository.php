<?php

namespace App\Repositories;

/**
 * Data access object for the 'exclusion_lists' table
 */
class ExclusionListRepository
{
    
    /**
     * Retrieves all rows from exclusion lists in the exclusion lists table. If
     * a filter is specified, then the returned rows are filtered by the parameters
     * set in the filter.
     * 
     * @param array filter (optional) array of key-value pairs with which to filter 
     * the  exclusion list data
     * @return array
     */
    public function get($filter = null)
    {
        $query = app('db')->table('exclusion_lists');
        
        if ($filter) {
            $query->where($filter);
        }

        return $query->get();
    }
    
    /**
     * Finds a record with the given prefix in the exclusion_lists table
     * @param String $prefix
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
    }
    
    /**
     * Returns true if the '<prefix>_records' table does not contain any data,
     * false otherwise
     * @param string $prefix the prefix of the exclusion list whose records table
     * will be checked
     */
    public function isExclusionListRecordsEmpty($prefix)
    {
        return app('db')->table($prefix . '_records')->count() === 0;
    }    
}
