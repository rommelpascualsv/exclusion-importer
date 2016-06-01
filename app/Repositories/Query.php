<?php
namespace App\Repositories;

class Query
{
    private $criteria = [];
    private $orderBy = '';
    private $orderByDirection = 'asc';
    private $table;

    public static function create()
    {
        return new Query();
    }
    
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }
    
    public function addCriteria(array $criteria)
    {
        $this->criteria = array_merge($this->criteria, $criteria);
        return $this;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function setOrderByDirection($orderByDirection)
    {
        $this->orderByDirection = $orderByDirection;
        return $this;
    }

    public function execute()
    {
        $query = app('db')->table($this->table);

        if ($this->criteria) {
            $query->where($this->criteria);
        }

        if ($this->orderBy) {
            $query->orderBy($this->orderBy, $this->orderByDirection ? $this->orderByDirection : 'asc');
        }

        return $query->get();

    }
        
}
