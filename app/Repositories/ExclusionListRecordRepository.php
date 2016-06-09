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
    
    public function getImportStats($prefix)
    {
        $db = app('db');
        
        $addedOrDeletedRecords = $this->getRecordsWithUnmatchedHashes($prefix);
        
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
            ->setPreviousRecordCount($db->connection('exclusion_lists')->table($prefix.'_records')->count())
            ->setCurrentRecordCount($this->size($prefix)); 
        
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
    
    private function getRecordsWithUnmatchedHashes($prefix)
    {
        $db = app('db');
        
        $stagingTable = $this->stagingSchema . '.' . $prefix . '_records';
        $prodTable = $this->prodSchema . '.' . $prefix . '_records';
        
        $stagingTableUnmatchedHashesQuery = $db->table($stagingTable)
            ->select($db->raw('*, \'staging\' as source'))
            ->whereNotIn('hash', function($query) use ($stagingTable, $prodTable) {
                $query->select($stagingTable.'.hash')
                      ->from($stagingTable)
                      ->join($prodTable, $stagingTable.'.hash', '=', $prodTable.'.hash');
            });

        $prodTableUnmatchedHashesQuery = $db->table($prodTable)
            ->select($db->raw('*, \'prod\' as source'))
            ->whereNotIn('hash', function($query) use ($stagingTable, $prodTable) {
                $query->select($prodTable.'.hash')
                      ->from($prodTable)
                      ->join($stagingTable, $stagingTable.'.hash', '=', $prodTable.'.hash');
            });
                
        $unmatchedHashesQuery = $stagingTableUnmatchedHashesQuery->unionAll($prodTableUnmatchedHashesQuery);
                
        return $unmatchedHashesQuery->get();
   }
}
