<?php

namespace App\Import\Scrape\Scrapers\Connecticut;

use App\Import\Scrape\Scrapers\Connecticut\Data\OptionCollection;
use Goutte\Client;
use App\Import\Scrape\Components\FilesystemInterface;

class CsvDownloader
{
	/**
	 * @var Client
	 */
	protected $client;
	
	/**
	 * @var OptionCollection
	 */
	protected $options;
	
	/**
	 * @var FilesystemInterface
	 */
	protected $filesystem;
	
	public function __construct(
			Client $client,
			OptionCollection $options,
			FilesystemInterface $filesystem
	) {
		$this->client = $client;
		$this->options = $options;
		$this->filesystem = $filesystem;
	}
	
	/**
	 * Download csvs
	 */
	public function download()
	{
		$mainPage = MainPage::scrape($this->client);
		$downloadOptionsPage = DownloadOptionsPage::scrape($mainPage, $this->options);
		$rosterIds = $this->getRosterIds($downloadOptionsPage);
		
		foreach ($this->options as $i => $option) {
			$rosterId = $rosterIds[$i];
			$csvPage = CsvPage::scrape($downloadOptionsPage, $rosterId);
			
			$this->filesystem->write(
					'csv/connecticut/' . $option->getFileName() . '.csv',
					$csvPage->getContent()
			);
		}	
	}
	
	/**
	 * Get roster ids
	 * @param DownloadOptionsPage $page
	 * @return array
	 */
	protected function getRosterIds(DownloadOptionsPage $page)
	{
		$rosterIds = [];
		
		foreach ($this->options as $option) {
			$rosterId = $page->getRosterId($option->getName());
			
			$rosterIds[] = $rosterId;
		}
		
		return $rosterIds;
	}
}