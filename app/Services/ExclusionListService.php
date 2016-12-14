<?php
namespace App\Services;

use App\Events\FileImportEvent;
use App\Repositories\FileImportEventRepository;
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
    private $fileImportEventRepo;

    public function __construct(ExclusionListRepository $exclusionListRepo,
                                ExclusionListFileRepository $exclusionListFileRepo,
                                ExclusionListStatusHelper $exclusionListStatusHelper,
                                FileImportEventRepository $fileImportEventRepo)
    {
        $this->exclusionListRepo = $exclusionListRepo;
        $this->exclusionListFileRepo = $exclusionListFileRepo;
        $this->exclusionListStatusHelper = $exclusionListStatusHelper;
        $this->fileImportEventRepo = $fileImportEventRepo;

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

            $lastEventForPrefix = $this->fileImportEventRepo->findLatestEventOfPrefix($prefix);

            $activeExclusionList->last_file_hash_changed = ($files ? $files[0]->date_created : null);

            $activeExclusionList->last_import_error = ($lastEventForPrefix && $lastEventForPrefix->getStatus() == FileImportEvent::EVENTSTATUS_FAIL ? $lastEventForPrefix->getDescription() : null);

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
