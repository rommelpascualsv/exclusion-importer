<?php
namespace App\Services;

use App\Repositories\ExclusionListFileRepository;
use App\Repositories\ExclusionListRepository;
use App\Repositories\ExclusionListVersionRepository;
use App\Services\Contracts\ExclusionListServiceInterface;

/**
 * Service class for retrieval and management of exclusion lists
 *
 */
class ExclusionListService implements ExclusionListServiceInterface
{
    private $exclusionListVersionRepo;
    private $exclusionListRepo;
    private $exclusionListFileRepo;
    
    public function __construct(ExclusionListVersionRepository $exclusionListVersionRepo,
            ExclusionListRepository $exclusionListRepo,
            ExclusionListFileRepository $exclusionListFileRepo)
    {
        $this->exclusionListVersionRepo = $exclusionListVersionRepo;
        $this->exclusionListRepo = $exclusionListRepo;
        $this->exclusionListFileRepo = $exclusionListFileRepo;
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
            
            $latestRepoFile = $this->getLatestRepoFileFor($prefix);
            
            $activeExclusionList->update_required = ! $latestRepoFile || ! $this->isExclusionListUpToDate($prefix, $latestRepoFile->hash);
            
            $collection[$prefix] = json_decode(json_encode($activeExclusionList), true);
        }
        return $collection;
    }
    
    private function getLatestRepoFileFor($prefix)
    {
        $files = $this->exclusionListFileRepo->getFilesForPrefix($prefix);
        return $files ? $files[0] : null;
    }
    
    private function isExclusionListUpToDate($prefix, $latestHash)
    {
        $records = $this->exclusionListVersionRepo->find($prefix);
        return $records && $records[0]->last_imported_hash === $latestHash; 
    }

}
