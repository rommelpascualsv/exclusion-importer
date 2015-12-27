<?php namespace App\Import\Service\File;

use League\Csv\Reader;

class CsvFileReader implements FileReader
{

    protected $csvReader;


    public function readRecords($fileName, $options = array())
    {
        $csvReader = Reader::createFromPath($fileName);

        $headerRow = (isset($options['headerRow'])) ? $options['headerRow'] : 0;

        $offset = isset($options['offset']) ? $options['offset'] : 1;

        $headers = $csvReader->fetchOne($headerRow);

        $csvReader->setOffset($offset);

        $records = $csvReader->fetchAll();

        array_pop($records);

        return [$headers, $records];
    }

}

