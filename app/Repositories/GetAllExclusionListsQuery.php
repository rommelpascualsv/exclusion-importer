<?php
namespace App\Repositories;

class GetAllExclusionListsQuery
{
    public function execute()
    {
        return app('db')->table('exclusion_lists')->get();
    }
}
