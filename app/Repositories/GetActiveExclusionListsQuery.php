<?php
namespace App\Repositories;

use App\Repositories\Query;

class GetActiveExclusionListsQuery implements Query
{
    public function execute()
    {
        return app('db')->table('exclusion_lists')->where('is_active', 1)->get();
    }
}
