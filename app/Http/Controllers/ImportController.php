<?php

namespace App\Http\Controllers;

use App\Import;
use Laravel\Lumen\Routing\Controller as BaseController;

class ImportController extends BaseController
{
    public function import($listPrefix)
    {
        $listImportManager = new Import\Manager($listPrefix);

        $listObject = $listImportManager->getList();

        $exclusionsRetriever = $listImportManager->getRetriever();

        $listObject = $exclusionsRetriever->retrieveData($listObject);

        return response()->json($listObject);
    }
}
