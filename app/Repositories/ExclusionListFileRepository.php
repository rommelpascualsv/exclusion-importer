<?php

namespace App\Repositories;

use Illuminate\Database\Connection;
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
     * Returns all records matching the given criteria
     */
    public function query($criteria = null)
    {
        $query = app('db')->table('files');
        
        if ($criteria) {
            $query->where($criteria);
        }
        
        return $query->get();    
    }    
    
}
