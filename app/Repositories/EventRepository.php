<?php
namespace App\Repositories;

use App\Events\Event;

class EventRepository implements Repository
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
     * Returns the latest event of the given object id.
     * 
     * @param string $objectId the object_id of the event to search
     * @param array $eventTypes [optional] array of event_type values to constrain which type of latest event should be
     * returned for the given object id
     * @return \App\Events\Event the Event corresponding to the latest event recorded for the prefix
     */
    public function findLatestEventOfObjectId($objectId, $eventTypes = null)
    {
        $query = app('db')->table('events')
            ->where('object_id', $objectId);

        if ($eventTypes) {
            $query->whereIn('event_type', $eventTypes);
        }

        $record = $query->orderBy('timestamp', 'desc')->first();
        
        return $record ? $this->mapRecord($record) : null;
    }
    
    private function mapRecord(\stdClass $record)
    {
        $event = new Event();
        
        $event->setDescription($record->description);
        $event->setEventType($record->event_type);
        $event->setObjectId($record->object_id);
        $event->setStatus($record->status);
        $event->setTimestamp($record->timestamp);
        
        return $event;
        
    }
}
