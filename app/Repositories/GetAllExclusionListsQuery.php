<?php
namespace App\Repositories;

use App\Repositories\Query;

class GetAllExclusionListsQuery implements Query
{
    public function execute()
    {
        return app('db')->table('exclusion_lists')->get();
    }
}
