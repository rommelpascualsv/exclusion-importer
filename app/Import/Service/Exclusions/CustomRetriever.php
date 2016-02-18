<?php namespace App\Import\Service\Exclusions;

use App\Import\Lists\ExclusionList;

class CustomRetriever extends Retriever
{
    /**
     * @param ExclusionList $list
     *
     * @return ExclusionList
     */
    public function retrieveData(ExclusionList $list)
    {
       $list->data = $list->customRetriever();

        return $list;
    }

}
