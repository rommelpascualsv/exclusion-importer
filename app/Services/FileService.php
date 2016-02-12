<?php
namespace App\Services;

use App\File;
use App\Services\Contracts\FileServiceInterface;
use App\Url;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\DomCrawler\Crawler;
use App\Import\Service\Exclusions\ListFactory;

/**
 * Service class that manages the files wherein it updates the database whenever there are 
 * changes from the import file.
 *
 */
class FileService implements FileServiceInterface
{
	/**
	 * Checks if state prefix is updateable or not.
	 * 
	 * @param sring $prefix The state prefix
	 */
	public function isStateUpdateable($prefix)
	{
		$record = File::where('state_prefix',$prefix)->where('ready_for_update', 'Y')->get();
    	
		return count($record) === 1 ? true : false;
	}
	
	/**
	 * Refreshes the records whenever there are changes found in the import file.
	 * 
	 * {@inheritDoc}
	 * @see \App\Services\Contracts\FileService::refreshRecords()
	 */
	public function refreshRecords()
	{
		echo 'inside refresh';
		
		$urls = $this->getUrls();
		
		// iterate import urls
		foreach ($urls as $url)
		{
			
			echo 'inside foreach';
			
			$import_url = $this->getRealUrl($url);
			
			try 
			{
				
				echo 'inside try';
				
				echo $import_url;
				
				if(!$this->isFileSupported($import_url))
				{
					
					echo 'not supported';
					
					info('File type is not supported.');
					return;
				}
				
				echo 'after file supported';
				
				// get the blob value of import file
				$blob = file_get_contents($import_url);
				
				// checks if state prefix already exists in Files table
				if ($this->isPrefixExists($url->state_prefix))
				{
					// compares the import file and the one saved in Files table
					if ($blob !== $this->getBlobOfFile($url->state_prefix))
					{
						// updates the blob column in Files table if imported file is different
						$this->updateBlob($blob, $url->state_prefix);
					}
				} else
				{
					// inserts a record in Files table if state prefix is not found
					$this->insertFile($blob, $url->state_prefix, $import_url);
				}
			}
			catch (\ErrorException $e)
			{
				error_log($e->getMessage());
				info($import_url. " Error occured while downloading file. Continuing to next url...");
				continue;
			}
		}
	}
	
	/**
	 * Updates the Urls table from StreamLine Compliance page.
	 * 
	 * {@inheritDoc}
	 * @see \App\Services\Contracts\FileServiceInterface::updateUrls()
	 */
	public function updateUrls()
	{
		$crawler = new Crawler(file_get_contents('https://www.streamlineverify.com/compliance'));
		
		$hrefs = $crawler->filter('ul.sf-list li span a')->each(function ($node){
			return [$node->text() => $node->attr('href')];
		});
		
		$this->dropUrlsTable();
		
		$this->createUrlsTable();
		
		$this->populateUrlsTable($hrefs);
		
	}

	/**
	 * Retrieves an array of import urls saved in the URL table.
	 * 
	 * @return array
	 */
	private function getUrls(){
		
		// updates the urls table
		//$this->updateUrls();
		
		// retrieves all urls
		return Url::all();
	}
	
	/**
	 * Checks if the state prefix already exists in File table.
	 * 
	 * @param string $prefix The state prefix
	 * @return boolean
	 */
	private function isPrefixExists($prefix){
		$urls = File::where('state_prefix',$prefix)->get();
    	
    	return count($prefix) > 0;
	}
	
	/**
	 * Retrieves the blob value from File table for a given state prefix.
	 * 
	 * @param string $prefix The state prefix
	 * @return blob
	 */
	private function getBlobOfFile($prefix){
		$files = File::where('state_prefix', $prefix)->get();
    	
    	return count($files) > 0 ? $files[0]->img_data : null;
	}
	
	/**
	 * Inserts a record to the File table.
	 * 
	 * @param blob $blob The blob value of the import file
	 * @param string $prefix The state prefix
	 * @param string $url The import url
	 * @return void
	 */
	private function insertFile($blob, $prefix, $url){
		$file = new File;
		$file->file_name = $this->getFileName($url);
		$file->state_prefix = $prefix;
		$file->img_data = $blob;
		$file->ready_for_update = 'Y';
		
		info('Saving '.$file->file_name.'...');
		$file->save();
	}
	
	/**
	 * Updates the blob data in File table for a given state prefix.
	 * 
	 * @param blob $blob The blob value of the import file
	 * @param string $prefix The state prefix
	 * @return void
	 */
	private function updateBlob($blob, $prefix){
		$affected = File::where('state_prefix', $prefix)
		->update(['img_data' => $blob]);
		
		info($affected.' file/s updated');
	}
	
	/**
	 * Returns the filename of the url.
	 *
	 * @param string $url The import url
	 * @return string
	 */
	private function getFileName($url)
	{
		return pathinfo($url, PATHINFO_FILENAME).'.'.pathinfo($url, PATHINFO_EXTENSION);
	}
	
	/**
	 * Checks if the import file is currently supported.
	 *
	 * @param string $url The import url
	 * @return boolean
	 */
	private function isFileSupported($url)
	{
		echo 'inside file supported';
		
		$filetypeArr = ['application/pdf','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/plain','text/csv', 'text/html; charset=utf-8', 'text/html'];
	
		try
		{
			echo 'inside try ulit';
			$arrHeaders = get_headers($url, 1);
			echo 'after try ulit';
		}
		catch (\ErrorException $e)
		{
			echo 'catch';
			throw new \ErrorException($e);
		}
	
// 		print_r($arrHeaders);
		print_r($arrHeaders['Content-Type']);
		print_r(in_array($arrHeaders['Content-Type'], $filetypeArr));
		return in_array($arrHeaders['Content-Type'], $filetypeArr);
	}
	
	/**
	 * Creates the URLS table
	 * 
	 * @return void
	 */
	private function createUrlsTable()
	{
		Schema::create('urls', function (Blueprint $table) {
			$table->increments('id');
			$table->string('state_prefix');
			$table->string('url');
			$table->string('dynamic');
			$table->timestamps();
		});
	}
	
	/**
	 * Drops the URLS table
	 * 
	 * @return void
	 */
	private function dropUrlsTable()
	{
		
// 		app('db')->statement('DROP TABLE IF EXISTS `urls`');
		Schema::dropIfExists('urls');
	}
	
	/**
	 * Populates the URLS table
	 * 
	 * @param array $hrefs The array of map state-url values
	 * @return void
	 */
	private function populateUrlsTable($hrefs)
	{
		$listFactory = new ListFactory();
		
		foreach ($hrefs as $href)
		{
			foreach ($href as $k => $v)
			{
				$url = new Url();
				$url->url = $v;
				$url->state_prefix = array_search($k, $listFactory->listMappings); 
				$url->dynamic = 'N'; //FIXME: set correct values here per prefix
				info('Saving '.$k.'...');
				$url->save();
			}
		}
	}
	
	/**
	 * Retrieves the specific url.
	 * 
	 * @param Url $url The eloquet Url object
	 */
	private function getRealUrl($url)
	{
		$import_url = $url->url;
		
		if ($url->dynamic === 'Y')
		{
			
			$listFactory = new ListFactory();
			$class = $listFactory->make($url->state_prefix);
			$import_url = $class->uri;
		}
		
		return $import_url;
	}
}