<?php namespace App\Import\Service\Exclusions;

use App\Import\Service\DataCsvConverter;
use App\Import\Service\File\CsvFileReader;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class RetrieverFactory
{
    public $retrieverMappings = [
        'txt' => 'csv',
        'csv' => 'csv',
        'xls' => 'csv',
        'xlsx' => 'csv',
        'tsv' => 'csv',
        'html' => 'html',
        'pdf' => 'pdf',
        'xml'  => 'xml',
        'custom'  => 'custom',
    ];

    public function make($type)
    {
        if (array_key_exists($type, $this->retrieverMappings)) {
            $retrieverType =  $this->retrieverMappings[$type];

            return $this->getRetriever($retrieverType);
        }

        throw new \RuntimeException("Unsupported Exclusion List type");
    }

    private function getRetriever($retrieverType)
    {
        switch ($retrieverType) {
            case 'pdf':
                return new PDFRetriever(new Client());

                break;

            case 'csv';
                return new CSVRetriever(
                    new DataCsvConverter(new CsvFileReader()),
                    new Client()
                );
                break;

            case 'html':
                return new HTMLRetriever(
                    new Crawler(),
                    new DataCsvConverter(new CsvFileReader())
                );

                break;

            case 'custom':
                return new CustomRetriever();

                break;

            case 'xml':
                return new XmlRetriever();

                break;
        }
    }
}
