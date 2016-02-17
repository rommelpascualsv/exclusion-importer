<?php namespace App\Import\Lists;


use App\Import\Service\DataCsvConverter;
use App\Import\Service\File\CsvFileReader;
use League\Flysystem\Filesystem;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;

class Iowa extends ExclusionList
{

    public $dbPrefix = 'ia1';


    public $uri = "https://dhs.iowa.gov/sites/default/files/2016-01-31.PI_.term-suspend-probation.zip";

    public $type = 'custom';

    public $shouldHashListName = true;


    public $fieldNames = [
        'sanction_start_date',
        'npi',
        'individual_last_name',
        'individual_first_name',
        'entity_name',
        'sanction',
        'sanction_end_date'
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


    public function customRetriever()
    {
        $filePath = '';

        $storagePath = storage_path('app') . '/' . $this->dbPrefix . '.zip';

        //copy($this->uri, $storagePath);

        $files = new Filesystem(new ZipArchiveAdapter($storagePath));

        foreach ($files->listContents() as $file) {
            if ($file['extension'] == 'xlsx') {

                $filePath = $file['path'];

                break;
            }
        };

        if ($filePath == '') {
            throw new \Exception('No Path');
        }

        $contents = file_get_contents('zip://' . $storagePath . '#' . $filePath);

        $files->deleteDir($storagePath);

        $dataConverter = new DataCsvConverter(new CsvFileReader);

        $this->data = $dataConverter->convertData($this, $contents);

        $this->data = $this->convertDatesToMysql($this->data, $this->dateColumns);

        return $this->data;

    }

    private function convertDatesToMysql($data, $dateColumns)
    {
        return array_map(function ($row) use ($dateColumns) {

            foreach ($dateColumns as $index) {
                if (strtotime($row[$index])) {
                    $date = new \DateTime($row[$index]);
                    $row[$index] = $date->format('Y-m-d');
                } else {
                    $row[$index] = null;
                }
            }

            return $row;

        }, $data);
    }


    public function preProcess($data)
    {
        foreach ($data as &$record) {
            if (preg_match_all('/[1][0-9]{9}/', $record[1], $matches, PREG_PATTERN_ORDER)) {
                $record[1] = implode(',', $matches[0]);
            } else {
                $record[1] = '';
            }
        }

        return $data;
    }


    public function postProcess($data)
    {
        return array_map(function ($record) {
            if ($record['entity_name'] == 'N/A') {
                $record['entity_name'] = null;
            }

            return $record;

        }, $data);
    }
}
