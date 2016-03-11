<?php
namespace App\Services;

use App\Import\Service\Exclusions\ListFactory;
use App\Import\Service\ListProcessor;
use App\Services\Contracts\ImportServiceInterface;
use Illuminate\Http\Request;
/**
 * Service class that handles the import related processes.
 *
 */
class ImportService implements ImportServiceInterface
{
	/**
	 * Retrieves the exclusion list
	 *
	 * @return list The exclusion list
	 */
	public function getExclusionList()
	{
		$lists = $this->getSupportedStateList();
		
		$states = app('db')->table('exclusion_lists')->
			select('prefix', 'accr', 'import_url')->
			whereIn('prefix', array_keys($lists))->get();

		$collection = [];
		foreach($states as $state)
		{
			$collection[$state->prefix] = json_decode(json_encode($state),true);
		}
		
		return array_merge_recursive($lists, $collection);
	}
	
	/**
	 * Imports the downloaded file to database
	 * @param $request The Request object from frontend
	 * @param $listPrefix The state prefix
	 *
	 * @return object The object containing the result of the operation
	 */
	public function importFile($url, $listPrefix)
	{
		// 1. Retrieves the corresponding state object
		try {
			$listObject = $this->getStateObject($url, $listPrefix);
		}
		catch(\RuntimeException $e)
		{
			return $this->createResponse($e->getMessage(), false);
		}
		
		// 2. Checks if state is updateable
		if (empty($url) && !$this->isStateUpdateable($listPrefix))
		{
			return $this->createResponse('State is already up-to-date.', false);
		}
		
		// 3. Retrieves data for a given file type
		$listObject->retrieveData();
		
		// 4. Insert records to state table
		$processingService = $this->getListProcessor($listObject);
		$processingService->insertRecords();
		
		// 5. Return successful response
		return $this->createResponse('', true);
	}
	
	/**
	 * Returns the corresponding exclusion list object for a given state prefix.
	 * 
	 * @param string $listPrefix the state prefix
	 * @return object The state-specific exclusion list object
	 */
	protected function getListObject($listPrefix)
	{
		$listFactory = new ListFactory();
		$listObject = $listFactory->make($listPrefix);
		
		return $listObject;
	}
	
	/**
	 * Retrieves the corresponding list processor based on the passed object.
	 * 
	 * @param object $listObject the exclusion list object
	 * @return object the list processor object
	 */
	protected function getListProcessor($listObject)
	{
		return new ListProcessor($listObject);
	}
	
	/**
	 * Retrieves the Url record from the URLS table for a given state prefix.
	 *
	 * @param string $prefix The state prefix
	 *
	 * @return url The Url record
	 */
	protected function getUrl($prefix)
	{
		$record = app('db')->table('exclusion_lists')->where('prefix', $prefix)->get();
	
		return $record[0]->import_url;
	}
	
	/**
	 * Updates the url of the state whenever a url is specified in the exclusion importer page.
	 *
	 * @param string $statePrefix
	 * @param string $stateUrl
	 */
	protected function updateStateUrl($statePrefix, $stateUrl) {
		$result = app('db')->table('exclusion_lists')->where('prefix', $statePrefix)->update(['import_url' => $stateUrl]);
		info('Updated '.$result.' urls for '.$statePrefix);
	
		return $result;
	}
	
	/**
	 * Checks if state prefix is updateable or not.
	 *
	 * @param sring $prefix The state prefix
	 *
	 * @return boolean true if state is updateable otherwise false
	 */
	protected function isStateUpdateable($prefix)
	{
		$record = app('db')->table('files')->where('state_prefix', $prefix)->get();
	
		return (count($record) === 0 || $record[0]->ready_for_update === 'Y') ? true : false;
	}
	
	/**
	 * Retrieves the corresponding state object for a given stte prefix.
	 * 
	 * @param Request $request
	 * @param string $listPrefix
	 * 
	 * @return object The state object
	 * @throws \RuntimeException
	 */
	private function getStateObject($url, $listPrefix)
	{
		try {
			$listObject = $this->getListObject($listPrefix);
		
			if ($url) {
				
				$newUri = htmlspecialchars_decode($url);
				$this->updateStateUrl($listPrefix, $newUri);
				$listObject->uri = $newUri;
			} else {
				
				$listObject->uri = $this->getUrl($listPrefix);
			}
		}
		catch(\RuntimeException $e)
		{
			throw new \RuntimeException($e->getMessage () . ': ' . $listPrefix );
		}
		
		return $listObject;
	}
	
	/**
	 * Returns the supported state list.
	 *
	 * @return list The prefix-state list
	 */
	private function getSupportedStateList() {
		return [ 
			'az1' => 'Arizona',
			'ak1' => 'Alaska',
			'ar1' => 'Arkansas',
			'ct1' => 'Connecticut',
			'cus_spectrum_debar' => 'Custom Spectrum Debar List',
			'dc1' => 'Washington Dc',
			'fdac' => 'FDA Clinical Investigators',
			'fdadl' => 'FDA Debarment List',
			'fl2' => 'Florida',
			'ga1' => 'Georgia',
			'healthmil' => 'TRICARE Sanctioned Providers',
			'ia1' => 'Iowa',
			'ks1' => 'Kansas',
			'ky1' => 'Kentucky',
			'la1' => 'Louisiana',
			'me1' => 'Maine',
			'mo1' => 'Missouri',
			'ms1' => 'Mississippi',
			'mt1' => 'Montana',
			'nc1' => 'North Carolina',
			'nd1' => 'North Dakota',
			'njcdr' => 'New Jersey',
			'nyomig' => 'New York',
			'oh1' => 'Ohio',
			'pa1' => 'Pennsylvania',
			'phs' => 'NHH PHS',
			'sc1' => 'South Carolina',
			'tn1' => 'Tennessee',
			'tx1' => 'Texas',
			'usdocdp' => 'US DoC Denied Persons List',
			'usdosd' => 'US DoS Debarment List',
			'unsancindividuals' => 'UN Sanctions Individuals',
			'unsancentities' => 'UN Sanctions Entities',
			'wa1' => 'Washington State',
			'wv2' => 'West Virginia',
			'wy1' => 'Wyoming' 
		];
	}
	
	/**
	 * Returns the response object for the given parameters.
	 * 
	 * @param string $message
	 * @param string $isSuccess
	 * 
	 * @return object The response object
	 */
	private function createResponse($message, $isSuccess) {
		return response()->json ( [ 
				'success' => $isSuccess,
				'msg' => $message 
		] );
	}
}