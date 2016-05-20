<?php

namespace CDM\Test\Acceptance;

use App\Services\ImportFileService;
use Illuminate\Support\Facades\DB;
use App\Import\Service\Exclusions\ListFactory;
use App\Import\Lists\HashUtils;
use CDM\Test\TestCase;

/**
 * Acceptance test for ImportFileService. 
 * 
 * To run, execute the following in the shell : vendor/bin/codecept run acceptance ImportFileServiceTest --debug
 * 
 * This test will only run its assertions for states that define both an 'input' and
 * 'expected results' file. For this class to correctly run tests on a particular state, 
 * the following conventions must be followed:
 * 
 * 1. The test import file for a particular state should be placed in 'tests/acceptance/files/import/input'
 * and must be named using the format :
 *  
 *     <state_prefix>-<multi_file_index>.<file_extension>  where 
 *     
 *     <state_prefix>     = the prefix of the state in the exclusion_lists table
 *     
 *     <multi_file_index> = Specifies the index of an input file. If there are multiple 
 *                          input files for a state, input files would then be named as 
 *                          <state-prefix>-0 for the first input file,
 *                          <state-prefix>-1 for the second input file,
 *                          <state-prefix>-2 for the third input file, and so on.
 *                          
 *     <file_extension>   = the file extension. This should be equal to the value 
 *                          of the '$type' field of the ExclusionList subclass representing 
 *                          the state      
 *     
 *     @example :
 *     1. New York : nyomig-0.txt
 *     2. Illinois : il1-0.xls
 *     3. Tennessee : tn1-0.pdf
 *     4. West Virginia : wv2-0.pdf, wv2-1.pdf, wv2-2.pdf
 * 
 * 2. The expected results should be placed in 'tests/acceptance/files/import/expected' in 
 * a json file named <state_prefix>.json and must contain an array of objects corresponding
 * to rows that need to be verified if they were inserted correctly in the database -
 * usually, these would be the first and last rows, along with any other rows that
 * need to be verified. Only one json file is expected per state.
 * 
 *  @example:
 *  1. New York : nyomig.json
 *  2. Illinois : il1.json
 *  3. Tennessee : tn1.json
 *  4. West Virginia : wv2.json
 * 
 * To limit the states to test, specify the prefixes of the states to test in the 
 * 'include' property of tests/acceptance/files/import/config.json. For example,
 * to test only New York and Tennessee, specify : "include" : ["nyomig", "tn1"].
 * This is useful when creating a new test for a particular state, so that the class
 * won't have to iterate over all other existing tests for the other states. 
 * To test all states, specify null or an empty array as the value of the 
 * 'include' config. 
 */
class ImportFileServiceTest extends TestCase
{
    private $configFile = 'tests/acceptance/files/import/config.json';
    private $inputFileRoot = 'tests/acceptance/files/import/input/';
    private $expectedResultsRoot = 'tests/acceptance/files/import/expected/';
    
    private $listFactory;
    private $importFileService;
    private $config;
    
    public function setUp()
    {
        parent::setUp();
        $this->app->withFacades();
        $this->listFactory = new ListFactory();
        $this->importFileService = $this->app->make(ImportFileService::class);
        $this->config = $this->loadConfig();
    }
    
    public function testImportFileService()
    {
        $exclusionLists = $this->getExclusionLists();
        
        foreach ($exclusionLists as $exclusionList) {

            $prefix = $exclusionList->prefix; 

            
            if (! empty($this->config['include']) &&  array_search($prefix, $this->config['include']) === false) {
                //Do not process if the 'include' config is not empty and prefix is not in the include array
                continue;
            }

            codecept_debug('Running acceptance test for : '.$prefix);
            
            $url = $exclusionList->import_url;
            
            $importFiles = $this->getImportFilesForPrefix($prefix);

            if (empty($importFiles)) {
                codecept_debug('    No input files detected for ' . $prefix . '. Skipping this state.');
                continue;
            }
            
            //Clear any existing in the table to ensure we are working the test 
            //data from the input file
            $this->truncateTable($this->getTableName($prefix));
            
            $imported = $this->importFile($prefix, $importFiles);

            $this->restoreExclusionListUrlTo($url, $prefix);
            
            if ($imported) {
                $this->verifyEntriesExistInDatabase($prefix);
            }
            
        }

    }
    
    private function loadConfig()
    {
        return json_decode(file_get_contents($this->configFile), true);    
    }
    
    private function getExclusionLists()
    {
        return DB::select('select * from exclusion_lists');
    }
    
    private function importFile($prefix, $importFiles)
    {
        //If there are import files, proceed with import. Otherwise return
        //false to signify upstream to skip verification of results
        if (! empty($importFiles)) {
            
            $this->importFileService->importFile($importFiles, $prefix);
            
            return true;
        }
        
        return false;
    }
    
    private function getImportFilesForPrefix($prefix)
    {
        $fileExtension = $this->getFileExtensionFor($prefix);
        
        if (! $fileExtension) {
            return '';    
        }
        
        $files = [];
        
        //Find all files in tests/acceptance/files/import/input/ for the prefix
        //(i.e. tn1-0.pdf, tn1-1.pdf, tn1-2.pdf)
        for ($i = 0; $i < 10; $i++) {
            
            $fileName = sprintf('%s-%d.%s', $prefix, $i, $fileExtension);
            
            $fullFilePath = $this->inputFileRoot.$fileName;
            
            if (file_exists($fullFilePath)) {
                $files[] = $fullFilePath;
            }
        }
        
        return implode(',', $files);
    }
    
    private function restoreExclusionListUrlTo($url, $prefix)
    {
        DB::update('update exclusion_lists set import_url = ? where prefix = ?', [$url, $prefix]);
    }
    
    private function getTableName($prefix)
    {
        return $prefix.'_records';
    }
    
    private function getFileExtensionFor($prefix)
    {
        $exclusionListClass = $this->getExclusionListClassFor($prefix);
        
        return $exclusionListClass !== null ? $exclusionListClass->type : null;
    }
    
    private function getExclusionListClassFor($prefix)
    {
        return ! isset($this->listFactory->listMappings[$prefix]) ? null : $this->listFactory->make($prefix);
    }
    
    private function truncateTable($table)
    {
        DB::table($table)->truncate();
    }
    
    private function verifyEntriesExistInDatabase($prefix)
    {
        $expectedRows = $this->getExpectedRows($prefix);
        
        $table = $this->getTableName($prefix);
        
        foreach ($expectedRows as $expectedRow) {
            //Generate the hash and verify that it was correctly inserted in the 
            //database as well
            $expectedRow['hash'] = $this->generateHash($expectedRow, $prefix);
            
            $this->seeInDatabase($table, $expectedRow);
        }
    }
    
    private function getExpectedRows($prefix)
    {
        $expectedResultsFilePath = $this->expectedResultsRoot.$prefix.'.json';
        
        $json = file_get_contents($expectedResultsFilePath);
        
        $rows = json_decode($json, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->fail('Failed to decode json for '.$prefix.' : '.json_last_error_msg());
            return null;
        }   
        return $rows;
    }
    
    private function generateHash($record, $prefix)
    {
        $this->normalizeDatesForHash($record);
        
        $hash = HashUtils::generateHash($record, $this->getExclusionListClassFor($prefix));
        
        return hex2bin($hash);       
    }
    
    private function normalizeDatesForHash(&$record)
    {
       foreach ($record as $columnName => $columnValue) {
           
           if ($columnValue === '0000-00-00') {
               $record[$columnName] = '';
           }
           
       }
    }
    
}