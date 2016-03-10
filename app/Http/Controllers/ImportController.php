<?php namespace App\Http\Controllers;

use App\Import;
use App\Import\Service\Exclusions\ListFactory;
use App\Import\Service\ListProcessor;
use Illuminate\Http\Request;
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
            'cus_spectrum_debar_records',
            'usdosd_records',
            'healthmil_records',
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
            'az1'                => 'Arizona',
            'ak1'                => 'Alaska',
            'ar1'                => 'Arkansas',
            'ct1'                => 'Connecticut',
            'cus_spectrum_debar' => 'Custom Spectrum Debar List',
            'dc1'                => 'Washington Dc',
            'fdac'               => 'FDA Clinical Investigators',
            'fdadl'              => 'FDA Debarment List',
            'fl2'                => 'Florida',
            'ga1'                => 'Georgia',
            'healthmil'          => 'TRICARE Sanctioned Providers',
            'ia1'                => 'Iowa',
            'ks1'                => 'Kansas',
            'ky1'                => 'Kentucky',
            'la1'                => 'Louisiana',
            'me1'                => 'Maine',
            'mi1'                => 'Michigan',
            'mo1'                => 'Missouri',
            'ms1'                => 'Mississippi',
            'mt1'                => 'Montana',
            'nc1'                => 'North Carolina',
            'nd1'                => 'North Dakota',
            'njcdr'              => 'New Jersey',
            'nyomig'             => 'New York',
            'oh1'                => 'Ohio',
            'pa1'                => 'Pennsylvania',
            'phs'                => 'NHH PHS',
            'sc1'                => 'South Carolina',
            'tn1'                => 'Tennessee',
            'tx1'                => 'Texas',
            'usdocdp'            => 'US DoC Denied Persons List',
            'usdosd'             => 'US DoS Debarment List',
            'unsancindividuals'  => 'UN Sanctions Individuals',
            'unsancentities'     => 'UN Sanctions Entities',
            'wa1'                => 'Washington State',
            'wv2'                => 'West Virginia',
            'wy1'                => 'Wyoming',
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

    public function import(Request $request, $listPrefix)
    {
        $this->initPhpSettings();

        $listFactory = new ListFactory();

        try {
            $listObject = $listFactory->make($listPrefix);

            if ($request->input('url')) {
                $newUri = htmlspecialchars_decode($request->input('url'));

                $listObject->uri = $newUri;

                app('db')->table('exclusion_lists')->where('prefix', $listPrefix)
                    ->update(['import_url' => $newUri]);
            }
        }
        catch(\RuntimeException $e)
        {
            return response()->json([
                'success'	=>	false,
                'msg'		=>	$e->getMessage() . ': ' . $listPrefix
            ]);
        }

        try{
            $listObject->retrieveData();
        }
        catch(\RuntimeException $e)
        {
            return response()->json([
                'success'	=>	false,
                'msg'		=>	$e->getMessage()
            ]);
        }

        $processingService = new ListProcessor($listObject);

        $processingService->insertRecords();

        return response()->json([
            'success'	=> true,
            'msg'		=> ''
        ]);
    }

    private function initPhpSettings()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '120');
    }
}
