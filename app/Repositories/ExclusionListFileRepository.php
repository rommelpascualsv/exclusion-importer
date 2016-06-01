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
    public function create($record)
    {
        app('db')->table('files')->insert($record);
    
        info('Added ' . $record['state_prefix'] . '-' . $record['img_data_index'] .' to files table');
    }
    
    public function clear()
    {
        app('db')->table('files')->truncate();
        info('Truncated files table');
    }
    
    /**
     * Finds a record with the given prefix and fileIndex in the files table
     * @param array $compositeKey the composite key of the file containing the following:
     * $compositeKey[0] = state_prefix
     * $compositeKey[1] = img_data_index
     */
    public function find($compositeKey)
    {
        return app('db')->table('files')->where([
            'state_prefix'   => $compositeKey[0],
            'img_data_index' => $compositeKey[1]
        ])->get();
    }
    
    /**
     * Updates a record with the given prefix and fileIndex in the files table
     * with the given data
     * @param array $compositeKey the composite key of the file containing the following:
     * $compositeKey[0] = state_prefix
     * $compositeKey[1] = img_data_index
     * @param array $data array containing the column values to save in the table
     */
    public function update($compositeKey, $data)
    {
        $prefix = $compositeKey[0];
        $fileIndex = $compositeKey[1];
        
        $affected = app('db')->table('files')
        ->where([
            'state_prefix' => $prefix,
            'img_data_index' => $fileIndex
        ])
        ->update($data);
        
        info('Updated file content of ' . $prefix. '-' . $fileIndex . ' in files table . ' . $affected . ' file(s) updated');
    }
    
    /**
     * Returns true if a record with the given compositeKey exists in the files table, 
     * otherwise returns false
     * @param array $compositeKey the composite key of the file containing the following:
     * $compositeKey[0] = state_prefix
     * $compositeKey[1] = img_data_index
     * @return boolean
     */
    public function contains($compositeKey)
    {
        return app('db')->table('files')->where([
            'state_prefix' => $compositeKey[0],
            'img_data_index' => $compositeKey[1]
        ])->count() > 0;
    }    
}
