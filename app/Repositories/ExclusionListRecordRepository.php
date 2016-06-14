<?php

namespace App\Repositories;

use App\Models\ImportStats;
/**
 * Repository for exclusion list records
 *
 */
class ExclusionListRecordRepository implements Repository
{
    
    const DEFAULT_STAGING_SCHEMA = 'exclusion_lists_staging';
    const DEFAULT_PROD_SCHEMA    = 'exclusion_lists';
    const DEFAULT_BACKUP_SCHEMA  = 'exclusion_lists_backup';
    
    private $listFactory;
    private $stagingSchema;
    private $prodSchema;
    private $backupSchema;
    
    public function __construct()
    {
        $this->stagingSchema = self::DEFAULT_STAGING_SCHEMA;
        $this->prodSchema = self::DEFAULT_PROD_SCHEMA;
        $this->backupSchema = self::DEFAULT_BACKUP_SCHEMA;
    }
    
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
    
    /**
     * Returns an App\\Models\\ImportStats object containing metrics for
     * how many records will potentially get affected if records in the target 
     * table are updated with records from the source table. 
     * 
     * @param string $prefix The exclusion list prefix whose import stats will
     * be returned. Required.
     * @param string $source [optional] the source table name. Defaults to 
     * 'exclusion_lists_staging.<prefix>_records' if not specified.
     * @param string $target [optional] the target table name. Defaults to 
     * 'exclusion_lists.<prefix>_records if not specified.
     * @return \App\Models\ImportStats
     */
    public function getImportStats($prefix, $source = null, $target = null)
    {
        $db = app('db');
        
        $source = $source ?: $this->stagingSchema . '.' . $prefix . '_records';
        $target = $target ?: $this->prodSchema . '.' . $prefix . '_records';
        
        $addedOrDeletedRecords = $this->getRecordsWithUnmatchedHashes($prefix, $source, $target);
        
        $deleted = [];
        $added   = [];
        
        if ($addedOrDeletedRecords) {
            
            foreach($addedOrDeletedRecords as $record) {
                
                if ($record->source === 'staging') {
                    $added[] = $record;
                } else {
                    $deleted[] = $record;
                }
            }
        }
        
        $importStats = (new ImportStats())->setAdded($added ? count($added) : 0)
            ->setDeleted($deleted ? count($deleted) : 0)
            ->setPreviousRecordCount($db->table($target)->count())
            ->setCurrentRecordCount($db->table($source)->count()); 
        
        return $importStats;
    }
    
    public function pushRecordsToProduction($prefix)
    {
        $db = app('db');
    
        $prodSchemaDotPrefix    = $this->prodSchema . '.' .$prefix;
        $stagingSchemaDotPrefix = $this->stagingSchema . '.' . $prefix;
        $backupSchemaDotPrefix  = $this->backupSchema . '.' . $prefix;
    
        // Copy *_records in exclusion_lists_staging to *_records_new table in exclusion_lists
        $db->statement('DROP TABLE IF EXISTS ' . $prodSchemaDotPrefix . '_records_new');
        $db->statement('CREATE TABLE ' . $prodSchemaDotPrefix . '_records_new LIKE ' . $prodSchemaDotPrefix . '_records');
        $db->statement('INSERT INTO '. $prodSchemaDotPrefix . '_records_new SELECT * FROM ' .$stagingSchemaDotPrefix . '_records');
    
        $db->statement('DROP TABLE IF EXISTS ' . $backupSchemaDotPrefix . '_records');
        // Rename current *_records in exclusion_lists to *_records in exclusion_lists_backup
        // Rename current *_records_new in exclusion_lists to *_records in exclusion_lists
        $db->statement('RENAME TABLE ' . $prodSchemaDotPrefix .'_records TO ' . $backupSchemaDotPrefix . '_records, ' . $prodSchemaDotPrefix .'_records_new TO ' . $prodSchemaDotPrefix . '_records');
        $db->statement('DROP TABLE IF EXISTS ' . $prodSchemaDotPrefix . '_records_new');
    }    
    
    public function getStagingSchema()
    {
        return $this->stagingSchema;
    }
    
    public function setStagingSchema($stagingSchema)
    {
        $this->stagingSchema = $stagingSchema;
        return $this;
    }
    
    public function getProdSchema()
    {
        return $this->prodSchema;
    }
    
    public function setProdSchema($prodSchema)
    {
        $this->prodSchema = $prodSchema;
        return $this;
    }
    
    
    public function getBackupSchema()
    {
        return $this->backupSchema;
    }
    
    public function setBackupSchema($backupSchema)
    {
        $this->backupSchema = $backupSchema;
        return $this;
    }    
    
    /**
     * Returns all records from source without a matching 'hash' column value in
     * target and vice versa.
     * 
     * @param string $prefix the exclusion list version prefix
     * @param string $source the source table name
     * @param string $target the target table name
     */
    private function getRecordsWithUnmatchedHashes($prefix, $source, $target)
    {
        $db = app('db');
        
        $stagingTableUnmatchedHashesQuery = $db->table($source)
            ->select($db->raw('*, \'staging\' as source'))
            ->whereNotIn('hash', function($query) use ($source, $target) {
                $query->select($source.'.hash')
                      ->from($source)
                      ->join($target, $source.'.hash', '=', $target.'.hash');
            });

        $prodTableUnmatchedHashesQuery = $db->table($target)
            ->select($db->raw('*, \'prod\' as source'))
            ->whereNotIn('hash', function($query) use ($source, $target) {
                $query->select($target.'.hash')
                      ->from($target)
                      ->join($source, $source.'.hash', '=', $target.'.hash');
            });
                
        $unmatchedHashesQuery = $stagingTableUnmatchedHashesQuery->unionAll($prodTableUnmatchedHashesQuery);

        return $unmatchedHashesQuery->get();
   }
}
