<?php namespace App\Repositories;

class SamRepository implements Repository
{

    const SAM_TABLE_NAME = 'sam_records';
    const SAM_TEMP_TABLE_NAME = 'sam_records_temp';


    /**
     * Create Sam record(s) by batch
     *
     * @param $record
     */
    public function create($record)
    {
        $chunks = array_chunk($record, 10);
        foreach ($chunks as $chunk) {
            app('db')->transaction(function () use ($chunk) {
                app('db')->table(self::SAM_TEMP_TABLE_NAME)->insert($chunk);
            });
        }
    }


    /**
     * Update Sam record using new_hash as key
     *
     * @param $toUpdate
     * @param $newHash
     * @return mixed
     */
    public function updateRecord($toUpdate, $newHash)
    {
        $affectedRows = app('db')->table(self::SAM_TEMP_TABLE_NAME)
            ->update($toUpdate)
            ->where(app('db')->raw("HEX(new_hash)"), $newHash);

        info('Total Records updated ' .$affectedRows);

        return $affectedRows;
    }


    /**
     * Deactivate Sam Record(s) using new_hash as key
     *
     * @param $toDeactivate
     * @return mixed
     */
    public function deactivateRecordsByHash($toDeactivate)
    {
        $sql = "UPDATE sam_records_temp SET Record_Status = 0 WHERE new_hash IN 
                  (unhex('" . implode("'),unhex('", $toDeactivate) . "'))";

        $affectedRows = app('db')->statement(app('db')->raw($sql));

        info('Total Records deactivated ' .$affectedRows);

        return $affectedRows;
    }

    public function clear()
    {
        throw new \Exception("This operation is not supported");
    }

    public function find($id)
    {
        throw new \Exception("This operation is not supported");
    }

    
}
