<?php
namespace App\Repositories;

use App\Repositories\Query;

class GetFilesForPrefixAndHashQuery implements Query
{
    private $prefix;
    
    private $hash;
    
    public function __construct($prefix, $hash)
    {
        $this->prefix = $prefix;
        $this->hash = $hash;
    }
    
    public function execute()
    {
        return app('db')->table('files')
                        ->where(['state_prefix' => $this->prefix, 'hash' => $this->hash])
                        ->get();
    }
}
