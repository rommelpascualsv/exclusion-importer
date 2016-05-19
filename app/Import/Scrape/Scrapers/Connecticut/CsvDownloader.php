<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use App\Exceptions\Scrape\Connecticut\DownloadOptionMissingException;
use App\Exceptions\Scrape\ScrapeException;
use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\ProgressTrackers\TracksProgress;
use App\Import\Scrape\Scrapers\Connecticut\Data\Option;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;
use Goutte\Client;

class CsvDownloader
{

    use TracksProgress;

    /**
     * @var string
     */
    protected static $path = 'csv/connecticut';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var OptionCollection
     */
    protected $options;

    /**
     * @var ScrapeFilesystemInterface
     */
    protected $filesystem;

    /**
     * Instantiate
     * 
     * @param Client $client
     * @param OptionCollection $options
     * @param FilesystemInterface $filesystem
     */
    public function __construct(
        Client $client,
        OptionCollection $options,
        ScrapeFilesystemInterface $filesystem
    ) {
        $this->client = $client;
        $this->options = $options;
        $this->filesystem = $filesystem;
    }

    /**
     * Get client
     * 
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get options
     * 
     * @return OptionCollection
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Download csv files
     */
    public function download()
    {
        try {
            $this->trackProgress('Crawling the Main Page...');

            $mainPage = MainPage::scrape($this->client);
            $this->trackInfoProgress('Success (URL: ' . $mainPage->getRequestUri() . ')');
            
            $this->trackProgress('Submitting form to crawl the Download Options Page...');
            
            $downloadOptionsPage = DownloadOptionsPage::scrape($mainPage, $this->options);
            $this->trackInfoProgress('Success (URL: ' . $downloadOptionsPage->getRequestUri() . ')');
            
            $this->trackProgress('Extracting roster IDs...');
            
            $csvData = $this->getCsvData($downloadOptionsPage);
            
            $this->trackProgress('Downloading csv files to ' . $this->filesystem->getPath(static::$path) . '...');
            
            foreach ($csvData as $data) {
                $this->downloadCsv($data, $downloadOptionsPage);
            }
        } catch (ScrapeException $e) {
            $this->trackErrorProgress($e->getMessage());
        }
    }

    /**
     * Get csv data for downloading csv files
     * 
     * @param DownloadOptionsPage $downloadOptionsPage
     * @return type
     */
    protected function getCsvData(DownloadOptionsPage $downloadOptionsPage)
    {
        $csvData = [];
        
        /** @var Option $option */
        foreach ($this->options as $option) {
            try {
                $rosterId = $downloadOptionsPage->getRosterId($option);
            } catch (DownloadOptionMissingException $e) {
                $this->trackErrorProgress($e->getMessage() . '. Proceeding to next option.');

                continue;
            }

            $this->trackInfoProgress('Got roster ID for ' . $option->getDescriptiveName());

            $csvData[] = [
                'roster_id' => $rosterId,
                'option' => $option
            ];
        }

        return $csvData;
    }

    /**
     * Download a csv file
     * 
     * @param type $data
     * @param DownloadOptionsPage $downloadOptionsPage
     */
    protected function downloadCsv($data, DownloadOptionsPage $downloadOptionsPage)
    {
        $fileName = $this->getFileName($data['option']);
        
        $this->trackProgress('Downloading ' . $fileName . '...');
        
        $csvPage = CsvPage::scrape($downloadOptionsPage, $data['roster_id']);
        
        $this->filesystem->put(
            static::$path . '/' . $fileName, $csvPage->getContent()
        );

        $this->trackInfoProgress('Downloaded ' . $fileName . ' from ' . $csvPage->getRequestUri());
    }

    /**
     * Get file name
     * 
     * @param Option $option
     * @return string
     */
    protected function getFileName(Option $option)
    {
        return $option->getCategory()->getDir() . '/' . $option->getFileName() . '.csv';
    }
}
