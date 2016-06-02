<?php
namespace App\Repositories;

use App\Repositories\Query;

class GetFilesForPrefixQuery implements Query
{
    private $prefix;
    
    private $orderBy;
    
    private $direction;
    
    public function __construct($prefix, $orderBy = 'date_last_downloaded', $direction = 'desc') 
    {
        $this->prefix = $prefix;
        $this->orderBy = $orderBy;
        $this->direction = $direction;
    }
    
    public function execute()
    {
        return app('db')->table('files')
                        ->where('state_prefix', $this->prefix)
                        ->orderBy($this->orderBy, $this->direction)
                        ->get();
    }
}
