<?php
namespace App\Services;

use App\Events\Event;
use App\Events\FileImportEventFactory;
use App\Repositories\EventRepository;
use App\Import\Lists\Sam;
use App\Services\Contracts\ExclusionListServiceInterface;
use App\Repositories\ExclusionListRepository;
use App\Repositories\FileRepository;

/**
 * Service class for retrieval and management of exclusion lists
 *
 */
class ExclusionListService implements ExclusionListServiceInterface
{
    private $exclusionListRepo;
    private $exclusionListFileRepo;
    private $exclusionListStatusHelper;
    private $eventRepo;

    public function __construct(ExclusionListRepository $exclusionListRepo,
                                FileRepository $exclusionListFileRepo,
                                ExclusionListStatusHelper $exclusionListStatusHelper,
                                EventRepository $eventRepo)
    {
        $this->exclusionListRepo = $exclusionListRepo;
        $this->exclusionListFileRepo = $exclusionListFileRepo;
        $this->exclusionListStatusHelper = $exclusionListStatusHelper;
        $this->eventRepo = $eventRepo;

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

            $lastEventForPrefix = $this->eventRepo->findLatestEventOfObjectId($prefix);

            $activeExclusionList->last_file_hash_changed = ($files ? $files[0]->date_created : null);

            $activeExclusionList->last_import_error = ($lastEventForPrefix && $lastEventForPrefix->getStatus() == Event::EVENTSTATUS_FAIL ? $lastEventForPrefix->getDescription() : null);

            $activeExclusionList->last_file_hash_changed = ($files ? $files[0]->date_created : null);

            if ($activeExclusionList->prefix == 'sam') {
                $sam = new Sam\SamService();
                $activeExclusionList->import_url = $sam->getUrl();
            }

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
