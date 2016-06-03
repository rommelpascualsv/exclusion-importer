<?php
namespace App\Repositories;

class GetFilesForPrefixQuery
{
    public function execute($prefix, $orderBy = 'date_last_downloaded', $direction = 'desc')
    {
        return app('db')->table('files')
                        ->where('state_prefix', $prefix)
                        ->orderBy($orderBy, $direction)
                        ->get();
    }
}
