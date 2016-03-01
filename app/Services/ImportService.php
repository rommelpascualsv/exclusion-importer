<?php
namespace App\Services;

use App\Import\Service\Exclusions\ListFactory;
use App\Import\Service\ListProcessor;
use App\Services\Contracts\FileServiceInterface;
use App\Services\Contracts\ImportServiceInterface;
use Illuminate\Http\Request;
/**
 * Service class that handles the import related processes.
 *
 */
class ImportService implements ImportServiceInterface
{
	protected $fileService;
	
	public function __construct(FileServiceInterface $fileService)
	{
		$this->fileService = $fileService;
	}
	
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
		if (empty($url) && !$this->fileService->isStateUpdateable($listPrefix))
		{
			return $this->createResponse('State is already up-to-date.', false);
		}
		
		// 3. Retrieves data for a given file type
		try{
			$listObject->retrieveData();
		}
		catch(\RuntimeException $e)
		{
			return $this->createResponse($e->getMessage(), false);
		}
		
		// 4. Insert records to state table
		$processingService = new ListProcessor($listObject);
		$processingService->insertRecords();
		
		// 5. Return successful response
		return $this->createResponse('', true);
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
			$listFactory = new ListFactory();
			$listObject = $listFactory->make($listPrefix);
		
			if ($url) {
				
				$newUri = htmlspecialchars_decode($url);
				$this->fileService->updateStateUrl($listPrefix, $newUri);
				$listObject->uri = $newUri;
			} else {
				
				$listObject->uri = $this->fileService->getUrl($listPrefix)[0]->url;
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