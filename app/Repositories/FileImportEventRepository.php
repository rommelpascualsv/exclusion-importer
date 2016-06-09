<?php
namespace App\Repositories;

use App\Events\FileImportEvent;
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
        $records = app('db')->table('events')->where('id', $id)->get();
        
        if (! $records) {
            return null;
        }
        
        $results = [];
        
        foreach ($records as $record) {
            $results[] = $this->mapRecord($record);
        }
        
        return $results;
    }
    
    /**
     * Returns the latest file import event of the given exclusion list prefix.
     * 
     * @param string $prefix the exclusion list prefix
     * @return \App\Events\FileImportEvent the FileImportEvent corresponding to 
     * the latest event recorded for the prefix
     */
    public function findLatestEventOfPrefix($prefix) 
    {
        $record = app('db')->table('events')
            ->where('object_id', $prefix)
            ->whereIn('event_type', FileImportEvent::EVENT_TYPES)
            ->orderBy('timestamp', 'desc')
            ->first();
        
        return $record ? $this->mapRecord($record) : null;
    }
    
    private function mapRecord(\stdClass $record)
    {
        $event = new FileImportEvent();
        
        $event->setDescription($record->description);
        $event->setEventType($record->event_type);
        $event->setObjectId($record->object_id);
        $event->setStatus($record->status);
        $event->setTimestamp($record->timestamp);
        
        return $event;
        
    }
}
