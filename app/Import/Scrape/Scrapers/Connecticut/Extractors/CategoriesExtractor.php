<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Extractors;

use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\Scrapers\Connecticut\MainPage;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Import\Scrape\ProgressTrackers\TracksProgress;

class CategoriesExtractor
{
    
    use TracksProgress;
    
    /**
     * @var MainPage
     */
    protected $page;

    /**
     * @var ScrapeFilesystemInterface
     */
    protected $filesystem;
    
    /**
     * @var array
     */
    protected $data = [];
    
    /**
     * @var string
     */
    protected static $saveFilePath = 'extracted/connecticut/connecticut-categories.json';
    
    /**
     * Initialize
     * 
     * @param ScrapeFilesystemInterface $filesystem
     */
    public function __construct(ScrapeFilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    
    /**
     * Get data
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Extract categories
     * 
     * @param Client $client
     * @return $this
     */
    public function extract(Client $client)
    {
        $this->trackProgress('Crawling the main page...');
        
        $this->scrapeMainPage($client);
        $this->trackInfoProgress('Main page crawled.');
        
        $this->trackProgress('Extracting the category data from the main page...');
        
        $panelNodes = $this->page->getPanelNodes();
        $data = [];
        
        $panelNodes->each(function(Crawler $panelNode, $i) use (&$data) {
            $categoryData = $this->extractCategoryData($panelNode, $i);
            
            $key = $this->getKey($categoryData['name']);
            $data[$key] = $categoryData;
            
            $this->trackInfoProgress('Extracted ' . $categoryData['name'] . ' data.');
        });
        
        $this->data = $data;
        
        return $this;
    }
    
    /**
     * Scrape main page and set page attribute
     * 
     * @param Client $client
     * @return MainPage
     */
    public function scrapeMainPage(Client $client)
    {
        $this->page = MainPage::scrape($client);
        
        return $this->page;
    }
    
    /**
     * Extract category data
     * 
     * @param Crawler $panelNode
     * @param int $i
     * @return array
     */
    public function extractCategoryData(Crawler $panelNode, $i)
    {
        $categoryName = $this->page->getCategoryText($panelNode, $i);
        $optionsData = $this->page->getOptionsData($panelNode, $i);
        
        foreach ($optionsData as $optionIndex => $data) {
            /* add key-based option data if unique */
            $key = $this->getKey($data['name']);
            if (! array_key_exists($key, $optionsData)) {
                /* add file name */
                $data['file_name'] = $key;
                
                $optionsData[$key] = $data;
            }
            
            /* remove index-based option data */
            unset($optionsData[$optionIndex]);
        }
        
        return [
            'name' => $categoryName,
            'options' => $optionsData
        ];
    }
    
    /**
     * Get key
     * 
     * @param string $name
     * @return string
     */
    protected function getKey($name)
    {
        return strtolower($this->underscorify($name));
    }
    
    /**
     * Underscorify text
     * 
     * @param string $text
     * @return string
     */
    public function underscorify($text)
    {
        return preg_replace(
            ['/[^a-zA-Z0-9 ]/', '/ /', '/_{2,}/', '/^_|_$/'],
            [' ', '_', '_', ''],
            $text
        );
    }
    
    /**
     * Save extracted data to JSON file
     */
    public function save()
    {
        $this->filesystem->put(
            static::$saveFilePath,
            json_encode($this->data, JSON_PRETTY_PRINT)
        );
        
        $filePath = $this->filesystem->getPath(static::$saveFilePath);
        
        $this->trackInfoProgress(
            'Extracted category data saved in ' . $filePath
        );
    }
}
