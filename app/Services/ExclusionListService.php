<?php
namespace App\Services;

use App\Services\Contracts\ExclusionListServiceInterface;
use App\Repositories\ExclusionListRepository;
use App\Repositories\ExclusionListFileRepository;

/**
 * Service class for retrieval and management of exclusion lists
 *
 */
class ExclusionListService implements ExclusionListServiceInterface
{
    private $exclusionListRepo;
    private $exclusionListFileRepo;
    private $exclusionListStatusHelper;
    
    public function __construct(ExclusionListRepository $exclusionListRepo,
        ExclusionListFileRepository $exclusionListFileRepo, 
        ExclusionListStatusHelper $exclusionListStatusHelper)
    {
        $this->exclusionListRepo = $exclusionListRepo;
        $this->exclusionListFileRepo = $exclusionListFileRepo;
        $this->exclusionListStatusHelper = $exclusionListStatusHelper;
        
    }
    /**
     * Retrieves the list of active states
     * @return array
     */ 
    public function getActiveExclusionLists()
    {
        $activeExclusionLists = $this->exclusionListRepo->getActiveExclusionLists();
        
        $collection = [];
        
        foreach ($activeExclusionLists as $activeExclusionList) {
            
            $prefix = $activeExclusionList->prefix;
            
            $activeExclusionList->update_required = $this->exclusionListStatusHelper->isUpdateRequired($prefix, $this->getLatestFileHashFor($prefix));

            $files = $this->exclusionListFileRepo->getFilesForPrefix($prefix);

            $activeExclusionList->last_check_for_changes = ($files ? $files[0]->date_last_downloaded : null);

            $collection[$prefix] = json_decode(json_encode($activeExclusionList), true);
        }
        return $collection;
    }
    
    private function getLatestFileHashFor($prefix)
    {
        $files = $this->exclusionListFileRepo->getFilesForPrefix($prefix);

        return $files ? $files[0]->hash : null;
    }
}
