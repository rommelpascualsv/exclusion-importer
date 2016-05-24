<?php

namespace App\Repositories;

/**
 * Data access object for the 'files' table.
 *   
 */
class ExclusionListFilesRepository
{
    /**
     * Returns true if a record with the given prefix and fileIndex exists in the
     * files table, otherwise returns false
     * @param string $prefix the state_prefix of the record to search
     * @param string $fileIndex the img_data_index of the record to search
     * @return boolean
     */
    public function exists($prefix, $fileIndex)
    {
        return app('db')->table('files')->where([
            'state_prefix' => $prefix,
            'img_data_index' => $fileIndex
        ])->count() > 0;
    }
    
    /**
     * Finds a record with the given prefix and fileIndex in the files table
     * @param string $prefix the state_prefix of the record to search
     * @param string $fileIndex the img_data_index of the record to search
     */
    public function find($prefix, $fileIndex)
    {
        return app('db')->table('files')->where([
            'state_prefix'   => $prefix,
            'img_data_index' => $fileIndex
        ])->get();
    }
    
    /**
     * Creates a record in the files table.
     * @param array $record array containing the column values to save in the table
     */
    public function create($record)
    {
        return app('db')->table('files')->insert($record);
        
        info('Saved file content of ' . $prefix . '-' . $fileIndex .' to files table');
    }
    
    /**
     * Updates a record with the given prefix and fileIndex in the files table
     * with the given data
     * @param string $prefix the state_prefix of the record to update
     * @param string $fileIndex the img_data_index of the record to update
     * @param array $data array containing the column values to save in the table
     */
    public function update($prefix, $fileIndex, $data)
    {
        $affected = app('db')->table('files')
        ->where([
            'state_prefix' => $prefix,
            'img_data_index' => $fileIndex
        ])
        ->update($data);
        
        info('Updated file content of ' . $prefix. '-' . $fileIndex . ' in files table . ' . $affected . ' file(s) updated');
    }
}
