<?php

namespace App\Repositories;

/**
 * Repository for exclusion list files
 *   
 */
class ExclusionListFileRepository implements Repository
{
    /**
     * Creates a record in the files table.
     * @param array $record array containing the column values to save in the table
     */
    public function create($record = null)
    {
        return app('db')->table('files')->insert($record);
    }
    
    public function clear()
    {
        app('db')->table('files')->truncate();
    }
    
    /**
     * Finds a record with the given id in the files table
     * @param string $id the id of the row in the files table to find
     */
    public function find($id)
    {
        return app('db')->table('files')->where(['id'=> $id])->get();
    }
    
    /**
     * Updates a record satisfying the given the criteria with the given data
     * @param array $criteria the criteria that the rows to update must match
     * @param array $data array containing the column values to save in the table
     */
    public function update($criteria = null, $data)
    {
        $query =  app('db')->table('files');
        
        if ($criteria) {
            $query->where($criteria);
        }
        
        return $query->update($data);
    }
    
    /**
     * Returns true if a record matching the given criteria exists in the repository,
     * otherwise returns false
     */
    public function contains($criteria)
    {
        return $criteria ? app('db')->table('files')->where($criteria)->count() > 0 : false;
    }
    
    /**
     * Returns all the files from the files repository associated with the given
     * prefix
     * @param string $prefix the prefix whose associated files will be searched
     * @param string $orderBy [optional] the column by which the files will be sorted. 
     * Defaults to 'date_last_downloaded' if not specified.
     * @param string $direction [optional] the sort direction (i.e. 'asc' or 'desc'). 
     * Defaults to 'desc' if not specified.
     * @return array array of stdClass objects representing the files associated
     * with the prefix.
     */
    public function getFilesForPrefix($prefix, $orderBy = 'date_last_downloaded', $direction = 'desc')
    {
        return app('db')->table('files')
            ->where('state_prefix', $prefix)
            ->orderBy($orderBy, $direction)
            ->get();
    }
    
    /**
     * Returns all files associated with the given prefix and hash
     * @param string $prefix the prefix whose associated files will be searched
     * @param string $hash the hash whose associated files will be searched
     * @return array array of stdClass objects representing the files associated
     * with the prefix and hash.
     */
    public function getFilesForPrefixAndHash($prefix, $hash)
    {
        return app('db')->table('files')
            ->where(['state_prefix' => $prefix, 'hash' => $hash])
            ->get();
    }
}
