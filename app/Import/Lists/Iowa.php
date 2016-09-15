<?php namespace App\Import\Lists;

use League\Flysystem\Filesystem;
use App\Import\Service\DataCsvConverter;
use App\Import\Service\File\CsvFileReader;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class Iowa extends ExclusionList
{
    public $dbPrefix = 'ia1';

    public $uri = "https://dhs.iowa.gov/sites/default/files/2016-01-31.PI_.term-suspend-probation.zip";

    public $type = 'zip';

    public $shouldHashListName = true;

    public $fieldNames = [
        'sanction_start_date',
        'npi',
        'individual_last_name',
        'individual_first_name',
        'entity_name',
        'sanction',
        'sanction_end_date',
        'provider_number'
    ];

    public $hashColumns = [
        'sanction_start_date',
        'npi',
        'individual_last_name',
        'individual_first_name',
        'entity_name',
        'sanction_end_date'
    ];

    public $dateColumns = [
        'sanction_start_date' => 0,
        'sanction_end_date' => 6
    ];

    protected $npiColumnName = "npi";
    
    /**
     * @inherit preProcess
     */
    public function preProcess()
    {
        $this->parse();
    	parent::preProcess();
    }
    
    /**
     * Parse the input data
     */
    private function parse()
    {
    	$data = [];
    		
    	// iterate each row
    	foreach ($this->data as $row) {
    		
    	    $npiColumnIndex = $this->getNpiColumnIndex();

            $row = array_map(function($value) {
                return strtoupper($value) != 'N/A' ? $value : '';
            }, $row);

    	    // set provider number
    	    $row = PNHelper::setProviderNumberValue($row, PNHelper::getProviderNumberValue($row, $npiColumnIndex));
    	    	
    	    // set npi number array
    	    $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $npiColumnIndex), $npiColumnIndex);
    	    	
    	    $data[] = $row;
    	}
    		
    	// set back to global data
    	$this->data = $data;
    }
    
    public function retrieveData()
    {
        $filePath = '';

        $storagePath = storage_path('app') . '/' . $this->dbPrefix . '.zip';

        copy($this->uri, $storagePath);

        $zipArchiveAdapter = new ZipArchiveAdapter($storagePath);

        $zipDirectory = new Filesystem($zipArchiveAdapter);

        foreach ($zipDirectory->listContents() as $file) {
            if ($file['extension'] == 'xlsx') {
                $filePath = $file['path'];
                continue;
            }

            $zipDirectory->delete($file['path']);
        };

        if ($filePath == '') {
            throw new \Exception('No Path');
        }

        $contents = file_get_contents('zip://' . $storagePath . '#' . $filePath);

        $dataConverter = new DataCsvConverter(new CsvFileReader);

        $this->data = $dataConverter->convertData($this, $contents);
    }
}
