<?php

namespace App\Import\Scrape\Scrapers\Connecticut\Extractors;



use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use League\Csv\Reader;
use League\Csv\Writer;

class CsvHeadersExtractor
{
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
	 * @return array
	 */
	public function getFiles()
	{
		return $this->files;
	}
	
	/**
	 * Get save file path
	 * @return string
	 */
	public function getSaveFilePath()
	{
		return $this->saveFilePath;
	}
	
	/**
	 * Get data
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Extract headers from path
	 * @param string $path
	 * @return array
	 */
	public function extractHeaders($path)
	{
		$reader = Reader::createFromPath($path);
		$headers = array_map('trim', $reader->fetchOne());
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
	 * @return $this
	 */
	public function extract()
	{		
		foreach ($this->files as $data) {
			$this->data[] = $this->getCsvLine($data);
		}
		
		return $this;
	}
	
	/**
	 * Save data
	 * @return $this
	 */
	public function save()
	{
		if (count($this->data) == 0) {
			return false;
		}
		
		$writer = Writer::createFromPath($this->saveFilePath, 'w+');
		$writer->insertAll($this->data);
		
		return $this;
	}
	
	/**
	 * Create
	 * @param ScrapeFilesystemInterface $filesystem
	 * @param string $dirPath
	 * @param string $saveFilePath
	 * @return static
	 */
	public static function create(
			ScrapeFilesystemInterface $filesystem,
			$dirPath = 'connecticut',
			$saveFilePath = 'extracted/connecticut/headers.csv'
	) {
		/* set files */
		$dirPath = 'csv/' . $dirPath;
		$directories = $filesystem->listContents($dirPath);
		$files = [];
		
		foreach ($directories as $dirData) {
			$dirFiles = $filesystem->listContents($dirData['path']);
			
			foreach ($dirFiles as $fileData) {
				$files[] = [
						'category' => $dirData['basename'],
						'option' => $fileData['filename'],
						'file_path' => $filesystem->getPath($fileData['path'])
				];
			}
		}
		
		/* create save path directory */
		$saveFileDirPath = dirname($saveFilePath);
		
		if (! $filesystem->has($saveFileDirPath)) {
			$filesystem->createDir($saveFileDirPath);
		}
		
		/* set save file path */
		$saveFilePath = $filesystem->getPath($saveFilePath);
		
		return new static($files, $saveFilePath);
	}
}