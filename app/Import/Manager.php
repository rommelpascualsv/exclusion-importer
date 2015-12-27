<?php namespace App\Import;


use App\Import\Service\DataCsvConverter;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\VarDumper\Cloner\Data;

class Manager
{

    public $configs;

    public function __construct($listPrefix)
    {
        $this->configs = config("import.$listPrefix");
    }

    public function getList()
    {
        if ($this->configs['class'] == NULL)
            throw new \RuntimeException("Unsupported Exclusion List prefix");

        $class = "App\\Import\\Lists\\{$this->configs['class']}";

        return new $class;
    }

    /**
     * @return \App\Import\Service\Exclusions\Retriever
     */
    public function getRetriever()
    {
        switch($this->configs['retriever']) {
            case 'pdf':
                return new Service\Exclusions\PDFRetriever(new Client());

                break;

            case 'csv';
                return new Service\Exclusions\CSVRetriever(
                    new DataCsvConverter(new Service\File\CsvFileReader()),
                    new Client()
                );
                break;

            case 'html':
                return new Service\Exclusions\HTMLRetriever(
                    new Crawler(),
                    new DataCsvConverter(new Service\File\CsvFileReader())
                );

                break;
        }
    }
}