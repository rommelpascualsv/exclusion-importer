<?php

namespace App\Http\Controllers;

use App\Import\Manager;
use Laravel\Lumen\Routing\Controller as BaseController;

class ImportController extends BaseController
{
    public function import($listPrefix)
    {
        $listImportManager = new Manager($listPrefix);

        return response()->json($listImportManager->configs);
    }
}
