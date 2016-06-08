<?php 
namespace App\Listeners;

use App\Repositories\FileImportEventRepository;
use App\Events\FileImportEvent;

class FileImportEventPersister extends Listener
{
    private $eventRepo;
    
    public function __construct(FileImportEventRepository $eventRepo)
    {
        $this->eventRepo = $eventRepo;
    }
    
    public function handle(FileImportEvent $event)
    {
        return $this->eventRepo->create($event);
    }
}
