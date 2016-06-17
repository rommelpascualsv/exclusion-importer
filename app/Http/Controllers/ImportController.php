<?php namespace App\Http\Controllers;

use App\Services\Contracts\ExclusionListServiceInterface;
use App\Services\Contracts\ImportFileServiceInterface;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Response\JsonResponse;
use App\Services\Contracts\FileUploadServiceInterface;
use App\Services\FileUploadException;

class ImportController extends BaseController
{
    private $exclusionListService;
    private $importFileService;
    private $fileUploadService;
    
    use JsonResponse;
    
    public function __construct(ExclusionListServiceInterface $exclusionListService,
        ImportFileServiceInterface $importFileService,
        FileUploadServiceInterface $fileUploadService)
    {
        $this->exclusionListService = $exclusionListService;
        $this->importFileService = $importFileService;
        $this->fileUploadService = $fileUploadService;

        $this->initPhpSettings();
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
            app('db')->statement('DROP TABLE IF EXISTS `exclusion_lists_backup`.`' . $list . '_older`');
            app('db')->statement('CREATE TABLE `exclusion_lists_backup`.`' . $list . '_older` LIKE `exclusion_lists_cdm`.`' . $list . '`');
            app('db')->statement('INSERT INTO `exclusion_lists_backup`.`' . $list . '_older` SELECT * FROM `exclusion_lists_cdm`.`' . $list . '`');
        }
	}

    public function index()
    {
        return view('import')->with('exclusionLists', $this->exclusionListService->getActiveExclusionLists());
    }

    public function import(Request $request, $listPrefix)
    {
        return $this->importFileService->importFile($request->input('url'), $listPrefix);
    }
    
    public function upload(Request $request)
    {   
        $prefix = trim($request->input('prefix'));
        
        try {
            
            if (empty($prefix)) {
                throw new FileUploadException('No exclusion list was specified in the request');
            }
            
            if (! $request->hasFile('file')) {
                throw new FileUploadException('No file was detected in the request');
            }
            
            $file = $request->file('file');
            
            if (! $file->isValid()) {
                throw new FileUploadException('The uploaded file is not valid');
            }            
            
            $fileUrl = $this->fileUploadService->uploadFile($file, $prefix);
            
            $fileImportResponse = $this->importFileService->importFile($fileUrl, $prefix);
            
            return $this->createFileUploadResponseFrom($fileImportResponse, $fileUrl, $prefix);
            
        } catch (\Exception $e) {
            
            return $this->createResponse('Failed to upload / import file : ' . $e->getMessage(), false, ['prefix' => $prefix]);
        }
    }

    private function initPhpSettings()
    {
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '300');
    }
    
    private function createFileUploadResponseFrom(\Illuminate\Http\JsonResponse $fileImportResponseJson, 
        $fileUrl, $prefix)
    {
        $fileImportResponse = $fileImportResponseJson->getData(true);
        
        $fileImportResponseData = isset($fileImportResponse['data']) ? $fileImportResponse['data'] : [];
        
        $fileUploadResponseData = [
            'prefix' => $prefix,
            'url' => $fileUrl
        ];
        
        $responseData = array_merge($fileImportResponseData, $fileUploadResponseData);
        
        return $this->createResponse($fileImportResponse['message'], $fileImportResponse['success'], $responseData);
    }
}
