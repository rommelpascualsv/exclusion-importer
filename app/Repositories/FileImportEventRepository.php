<?php
namespace App\Repositories;

use App\Repositories\Repository;

class FileImportEventRepository implements Repository
{
    public function create($event)
    {
        return app('db')->table('events')->insert([
            'timestamp'   => $event->getTimestamp(),
            'event_type'  => $event->getEventType(),
            'description' => $event->getDescription(),
            'status'      => $event->getStatus(),
            'object_id'   => $event->getObjectId()
        ]);
    }
    
    public function clear()
    {
        app('db')->table('events')->truncate();
    }
    
    public function find($id)
    {
        //TODO : Implement me
    }
}
