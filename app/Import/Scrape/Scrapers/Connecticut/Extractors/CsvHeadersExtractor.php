<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Extractors;

use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use League\Csv\Reader;
use League\Csv\Writer;
use App\Import\Scrape\Scrapers\Connecticut\Data\CsvDir;
use App\Import\Scrape\ProgressTrackers\TracksProgress;

class CsvHeadersExtractor
{
    
    use TracksProgress;
    
	/**
	 * @var array
	 */
	protected $files;
	
	/**
	 * @var string
	 */
	protected $saveFilePath = '';
	
	/**
	 * @var array
	 */
	protected $data = [];
	
	public function __construct(array $files = [], $saveFilePath = '')
	{
		$this->files = $files;
		$this->saveFilePath = $saveFilePath;
	}
	
	/**
	 * Get files
     * 
	 * @return array
	 */
	public function getFiles()
	{
		return $this->files;
	}
	
	/**
	 * Get save file path
     * 
	 * @return string
	 */
	public function getSaveFilePath()
	{
		return $this->saveFilePath;
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
	 * Extract headers from path
     * 
	 * @param string $path
	 * @return array
	 */
	public function extractHeaders($path)
	{
		$reader = Reader::createFromPath($path);
		$headers = array_map(function($item) {
			return strtolower(trim($item));
		}, $reader->fetchOne());
		$lastHeadersIndex = count($headers) - 1;
		
		/* remove leading empty values */
		for ($i = $lastHeadersIndex; $i >= 0; $i--) {
			if ($headers[$i] == '') {
				unset($headers[$i]);
			} else {
				break;
			}
		}
		
		return array_values($headers);
	}
	
	/**
	 * Get csv line
     * 
	 * @param array $data
	 * @return array
	 */
	public function getCsvLine(array $data)
	{
		$headers = $this->extractHeaders($data['file_path']);
		
		return array_merge(
				[$data['category'], $data['option']],
				$headers
		);
	}
	
	/**
	 * Extract data
     * 
	 * @return $this
	 */
	public function extract()
	{		
        $this->trackProgress('Extracting headers in downloaded csv files in ' . $this->saveFilePath . ' ...');
        
		foreach ($this->files as $data) {
			$this->data[] = $this->getCsvLine($data);
		}
        
        $this->trackInfoProgress('Extracted headers from ' . count($this->data) . ' csv files');
        
		return $this;
	}
	
	/**
	 * Save data
     * 
	 * @return $this
	 */
	public function save()
	{
		if (count($this->data) == 0) {
			return false;
		}
		
		$saveFileDir = dirname($this->saveFilePath);
		
		if (! is_dir($saveFileDir)) {
		    mkdir($saveFileDir);
		}
		
		$writer = Writer::createFromPath($this->saveFilePath, 'w+');
		$writer->insertAll($this->data);
        
        $this->trackInfoProgress('Extracted headers saved in ' . $this->saveFilePath);
		
		return $this;
	}
	
	/**
	 * Create
     * 
	 * @param ScrapeFilesystemInterface $filesystem
	 * @param string $dirPath
	 * @param string $saveFilePath
	 * @return static
	 */
	public static function create(
			ScrapeFilesystemInterface $filesystem,
			$dirPath = 'csv/connecticut',
			$saveFilePath = 'extracted/connecticut/headers.csv'
	) {
	    $files = CsvDir::getDataFromFilesystem($filesystem, $dirPath);
		$saveFilePath = $filesystem->getPath($saveFilePath);
	    
		return new static($files, $saveFilePath);
	}
}