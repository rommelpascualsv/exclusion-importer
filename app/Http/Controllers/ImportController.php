<?php

namespace App\Http\Controllers;

use App\Import;
use App\Import\Service\ListProcessor;
use Laravel\Lumen\Routing\Controller as BaseController;

class ImportController extends BaseController
{

    public function index()
    {
        $lists = [
            'az1'    => 'Arizona',
            'ak1'    => 'Alaska',
            'ar1'    => 'Arkansas',
            'ct1'    => 'Connecticut',
            'dc1'    => 'Washington Dc',
            'fl2'    => 'Florida',
            'ga1'    => 'Georgia',
            'ia1'    => 'Iowa',
            'ks1'    => 'Kansas',
            'ky1'    => 'Kentucky',
            'la1'    => 'Louisiana',
            'me1'    => 'Maine',
            'mo1'    => 'Missouri',
            'ms1'    => 'Mississippi',
            'mt1'    => 'Montana',
            'nc1'    => 'North Carolina',
            'nd1'    => 'North Dakota',
            'nyomig' => 'New York',
            'njcdr'  => 'New Jersey',
            'oh1'    => 'Ohio',
            'pa1'    => 'Pennsylvania',
            'wa1'    => 'Washington State',
            'wv2'    => 'West Virginia',
            'wy1'    => 'Wyoming',
        ];

        $states = app('db')->table('exclusion_lists')->select(
            'prefix',
            'accr',
            'import_url'
        )->whereIn('prefix', array_keys($lists))->get();

        $collection = [];
        foreach($states as $state)
        {
            $collection[$state->prefix] = json_decode(json_encode($state),true);
        }

        $exclusionLists = array_merge_recursive($lists, $collection);

        return view('import')->with('exclusionLists', $exclusionLists);
    }


    public function import($listPrefix)
    {
        $listImportManager = new Import\Manager($listPrefix);

        $listObject = $listImportManager->getList();

        $exclusionsRetriever = $listImportManager->getRetriever();

        $listObject = $exclusionsRetriever->retrieveData($listObject);

        $processingService = new ListProcessor($listObject);

        $processingService->insertRecords();

        return response()->json([
            'success'	=> true,
            'msg'		=> ''
        ]);
    }
}
