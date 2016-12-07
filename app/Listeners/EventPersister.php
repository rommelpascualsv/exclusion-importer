<?php 
namespace App\Listeners;

use App\Events\Event;
use App\Repositories\EventRepository;

class EventPersister extends Listener
{
    private $eventRepo;
    
    public function __construct(EventRepository $eventRepo)
    {
        $this->eventRepo = $eventRepo;
    }
    
    public function handle(Event $event)
    {
        return $this->eventRepo->create($event);
    }
}
