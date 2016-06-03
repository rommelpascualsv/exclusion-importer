<?php
namespace App\Repositories;

class GetActiveExclusionListsQuery
{
    public function execute()
    {
        return app('db')->table('exclusion_lists')->where('is_active', 1)->get();
    }
}
