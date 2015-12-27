<?php

namespace App\Http\Controllers;

use App\Import;
use Laravel\Lumen\Routing\Controller as BaseController;

class ImportController extends BaseController
{
    public function import($listPrefix)
    {
        $listImportManager = new Import\Manager($listPrefix);

        $list = $listImportManager->getList();

        $exclusionsRetriever = $listImportManager->getRetriever();

        return response()->json([$listImportManager, $list, $exclusionsRetriever]);
    }
}
