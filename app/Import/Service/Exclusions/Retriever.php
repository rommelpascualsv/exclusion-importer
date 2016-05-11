<?php namespace App\Import\Service\Exclusions;

use App\Import\Lists\ExclusionList;

abstract class Retriever
{
    /**
     * Retrieve the data and fill the passed object's data property
     * @param ExclusionList $list
     * @return
     */
    abstract public function retrieveData(ExclusionList $list);

    public function multipleUri($uri)
    {
        $url = explode(',', $uri);

        return array_map(function ($item) {
            return trim($item);
        }, $url);

    }
}
