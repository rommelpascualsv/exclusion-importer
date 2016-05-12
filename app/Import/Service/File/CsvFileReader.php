<?php namespace App\Import\Service\File;

use League\Csv\Reader;

class CsvFileReader implements FileReader
{
    protected $csvReader;

    public function readRecords($fileName, $options = array())
    {
        $csvReader = Reader::createFromPath($fileName);

        $csvReader->setOffset(isset($options['offset']) ? $options['offset'] : 1);

        return $csvReader->fetchAll();
    }
}
