<?php
namespace App\Services;

use App\Repositories\ExclusionListVersionRepository;
use App\Repositories\GetActiveExclusionListsQuery;
use App\Services\Contracts\ExclusionListServiceInterface;
use App\Repositories\GetFilesForPrefixQuery;

/**
 * Service class for retrieval and management of exclusion lists
 *
 */
class ExclusionListService implements ExclusionListServiceInterface
{
    
    private $exclusionListVersionRepo;
    private $getActiveExclusionListsQuery;
    private $getFilesForPrefixQuery;
    
    public function __construct(ExclusionListVersionRepository $exclusionListVersionRepo,
            GetActiveExclusionListsQuery $getActiveExclusionListsQuery,
            GetFilesForPrefixQuery $getFilesForPrefixQuery)
    {
        $this->exclusionListVersionRepo = $exclusionListVersionRepo;
        $this->getActiveExclusionListsQuery = $getActiveExclusionListsQuery;
        $this->getFilesForPrefixQuery = $getFilesForPrefixQuery;
    }
    
    /**
     * Retrieves the list of active states
     * @return array
     */ 
    public function getActiveExclusionLists()
    {
        $activeExclusionLists = $this->getActiveExclusionListsQuery->execute();
        
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
        $files = $this->getFilesForPrefixQuery->execute($prefix);
        return $files ? $files[0] : null;
    }
    
    private function isExclusionListUpToDate($prefix, $latestHash)
    {
        $records = $this->exclusionListVersionRepo->find($prefix);
        return $records && $records[0]->last_imported_hash === $latestHash; 
    }

}
