<?php

namespace App\Http\Controllers;

use App\Import;
use App\Import\Service\ListProcessor;
use Laravel\Lumen\Routing\Controller as BaseController;

class ImportController extends BaseController
{

    public function createOldTables()
    {
        $lists = [
            'ak1_records',
            'dc1_records',
            'ks1_records',
            'ky1_records',
            'la1_records',
            'me1_records',
            'mo1_records',
            'ms1_records',
            'mt1_records',
            'nc1_records',
            'nd1_records',
            'oh1_records',
            'pa1_records',
            'wa1_records',
            'wv2_records',
            'wy1_records',
            'al1_records',
            'ar1_records',
            'az1_records',
            'ca1_records',
            'ct1_records',
            'fl2_records',
            'ga1_records',
            'hi1_records',
            'ia1_records',
            'id1_records',
            'il1_records',
            'ky1_records',
            'ma1_records',
            'md1_records',
            'mi1_records',
            'mn1_records',
            'ne1_records',
            'njcdr_records',
            'nv1_records',
            'nyomig_records',
            'oh1_records',
            'oig_records',
            'pa1_records',
            'sc1_records',
            'tn1_records',
            'tx1_records',
            'wy1_records',
            'sdn_address_list',
            'sdn_aka_list',
            'sdn_citizenship_list',
            'sdn_date_of_birth_list',
            'sdn_entries',
            'sdn_id_list',
            'sdn_import_log',
            'sdn_nationality_list',
            'sdn_place_of_birth_list',
            'sdn_program_list',
            'sdn_vessel_info',
            'cus_spectrum_debar_records'
        ];
        foreach ($lists as $list) {
            app('db')->statement('DROP TABLE IF EXISTS `' . $list . '_older`');
            app('db')->statement('CREATE TABLE  `' . $list . '_older` LIKE `' . $list . '`');
            app('db')->statement('INSERT  INTO `' . $list . '_older` SELECT * FROM `' . $list . '`');
        }
    }

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
