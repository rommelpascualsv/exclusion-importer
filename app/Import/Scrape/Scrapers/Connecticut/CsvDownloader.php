<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\Scrapers\Connecticut\Data\Option;
use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;
use Goutte\Client;
use App\Import\Scrape\Scrapers\Page;

class CsvDownloader
{	
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
	 * @return Client
	 */
	public function getClient()
	{
		return $this->client;
	}
	
	/**
	 * Get options
	 * @return OptionCollection
	 */
	public function getOptions()
	{
		return $this->options;
	}
	
	/**
	 * Scrape main page
	 * @return MainPage
	 */
	public function scrapeMainPage()
	{
		return MainPage::scrape($this->client);
	}
	
	/**
	 * Scrape download options page
	 * @param MainPage $page
	 */
	public function scrapeDownloadOptionsPage(MainPage $page)
	{
		return DownloadOptionsPage::scrape($page, $this->options);
	}
	
	/**
	 * Get roster ID
	 * @param string $optionName
	 * @param DownloadOptionsPage $downloadOptionsPage
	 * @return string
	 */
	public function getRosterId($optionName, DownloadOptionsPage $page)
	{
		return $page->getRosterId($optionName);
	}
	
	/**
	 * Get download path
	 * @return string
	 */
	public function getDownloadPath()
	{
		return $this->filesystem->getAdapter()->applyPathPrefix(static::$path);
	}
	
	/**
	 * Get file name
	 * @param Option $option
	 * @return string
	 */
	public function getFileName(Option $option)
	{
		return $option->getCategory()->getDir() . DIRECTORY_SEPARATOR . $option->getFileName() . '.csv';
	}
	
	/**
	 * Download file
	 * @param array $data
	 * @param DownloadOptionsPage $page
	 * @return CsvPage
	 */
	public function downloadFile(array $data, DownloadOptionsPage $page)
	{
		$csvPage = CsvPage::scrape($page, $data['roster_id']);
		
		$this->filesystem->put(
			static::$path . '/' . $data['file_name'],
			$csvPage->getContent()
		);
		
		return $csvPage;
	}
	
	/**
	 * Debug page html
	 * @param Page $page
	 */
	public function debug(Page $page)
	{
		$this->filesystem->put(static::$path . '/debug.html', $page->getHtml());
	}
}