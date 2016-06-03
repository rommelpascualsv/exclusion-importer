<?php
namespace App\Repositories;

class GetFilesForPrefixAndHashQuery
{
    public function execute($prefix, $hash)
    {
        return app('db')->table('files')
                        ->where(['state_prefix' => $prefix, 'hash' => $hash])
                        ->get();
    }
}
