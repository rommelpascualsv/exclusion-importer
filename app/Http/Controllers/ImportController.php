<?php namespace App\Http\Controllers;

use App\Services\Contracts\ImportServiceInterface;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ImportController extends BaseController
{
	protected $importService;
	
	public function __construct(ImportServiceInterface $importService)
	{
		$this->importService = $importService;
	}
	
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
        return view('import')->with('exclusionLists', $this->importService->getExclusionList());
    }
    
    public function import(Request $request, $listPrefix)
    {
        $this->initPhpSettings();
        
        return $this->importService->importFile($request, $listPrefix);
    }

    private function initPhpSettings()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '120');
    }
}
