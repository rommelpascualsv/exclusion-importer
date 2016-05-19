<?php namespace Test\Unit;

use App\Import\Lists\Arizona;
use App\Import\Lists\ExclusionList;
use App\Import\Lists\Iowa;
use App\Import\Lists\Kentucky;
use App\Import\Lists\Minnesota;
use App\Import\Lists\Mississippi;
use App\Import\Lists\NewYork;
use App\Import\Lists\Tennessee;
use App\Import\Lists\UnSanctionsIndividuals;
use App\Services\ExclusionListDownloader;
use App\Services\ExclusionListHttpDownloader;

/**
 * vendor/bin/codecept run unit Import/Services/ExclusionListHttpDownloaderTest --debug
 */
class ExclusionListHttpDownloaderTest extends \Codeception\TestCase\Test
{
    private $downloader;
    private $downloadDirectory;
    
    protected function _before()
    {
        $this->downloader = new ExclusionListHttpDownloader();
        
        $this->downloadDirectory = base_path('tests/unit/Services/download-test');
        
        $this->downloader->setDownloadDirectory($this->downloadDirectory);
    }
    
    public function testDefaultDownloadDirectoryIsSetAsDownloadDirectoryWhenNoDownloadDirectoryIsSet() 
    {
        $downloader = new ExclusionListHttpDownloader();
        $this->assertEquals(storage_path('app/imports/latest'), $downloader->getDownloadDirectory());
    }
    
    
    public function testDownloadSingleRemotePDFFile()
    {
        $exclusionList = new Tennessee();
        
        $expected = $this->getExpectedFileNamesFor($exclusionList, 1);
        
        $this->deleteFiles($expected);
        
        $files = $this->downloader->downloadFiles($exclusionList);
        
        $this->verifyExpectedFilesExistThenDelete($expected, $files);        
        
    }
    
    public function testDownloadMultipleFiles()
    {
        $exclusionList = new Minnesota();
        $exclusionList->uri = 'http://www.dhs.state.mn.us/main/idcplg?IdcService=GET_FILE&RevisionSelectionMethod=LatestReleased&Rendition=Primary&allowInterrupt=1&noSaveAs=1&dDocName=dhs16_177447,http://www.dhs.state.mn.us/main/idcplg?IdcService=GET_FILE&RevisionSelectionMethod=LatestReleased&Rendition=Primary&allowInterrupt=1&noSaveAs=1&dDocName=dhs16_177448';
    
        $expected = $this->getExpectedFileNamesFor($exclusionList, 2);
    
        $this->deleteFiles($expected);
    
        $files = $this->downloader->downloadFiles($exclusionList);
    
        $this->verifyExpectedFilesExistThenDelete($expected, $files);        
    }   
    
    public function testDownloadFilesOverHttps()
    {
        $exclusionList = new Mississippi();
    
        $expected = $this->getExpectedFileNamesFor($exclusionList, 1);
    
        $this->deleteFiles($expected);
    
        $files = $this->downloader->downloadFiles($exclusionList);
    
        $this->verifyExpectedFilesExistThenDelete($expected, $files);        
    }   
    
    public function testDownloadXML()
    {
        $exclusionList = new UnSanctionsIndividuals();
    
        $expected = $this->getExpectedFileNamesFor($exclusionList, 1);
    
        $this->deleteFiles($expected);
    
        $files = $this->downloader->downloadFiles($exclusionList);
    
        $this->verifyExpectedFilesExistThenDelete($expected, $files);        
    }    
    
    public function testDownloadTxt()
    {
        $exclusionList = new NewYork();
    
        $expected = $this->getExpectedFileNamesFor($exclusionList, 1);
    
        $this->deleteFiles($expected);
    
        $files = $this->downloader->downloadFiles($exclusionList);
    
        $this->verifyExpectedFilesExistThenDelete($expected, $files);        
    }
    
//     public function testDownloadZip()
//     {
//         $exclusionList = new Iowa();
    
//         $expected = $this->getExpectedFileNamesFor($exclusionList, 1);
    
//         $this->deleteFiles($expected);
    
//         $files = $this->downloader->downloadFiles($exclusionList);
    
//         $this->verifyExpectedFilesExistThenDelete($expected, $files);        

//     }
    
    public function testDownloadHtml()
    {
        $exclusionList = new Arizona();
    
        $files = $this->downloader->downloadFiles($exclusionList);
    
        $this->assertNull($files);
    
    }   
    
    public function testDownloadCrawlerBasedUri()
    {
        $exclusionList = new Kentucky();
    
        $expected = $this->getExpectedFileNamesFor($exclusionList, 1);
        
        $this->deleteFiles($expected);
        
        $files = $this->downloader->downloadFiles($exclusionList);
        
        $this->verifyExpectedFilesExistThenDelete($expected, $files);
        
    }    
    
    private function verifyExpectedFilesExistThenDelete($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
        
        foreach ($actual as $filename) {
            $this->assertTrue(is_file($filename), 'File '. $filename . ' was not found in the download directory or is not file.');    
        }
        
        $this->deleteFiles($actual);
        
    }
    
    private function deleteFiles($filenames)
    {
        foreach ($filenames as $filename) {
            if (file_exists($filename)) {
                unlink($filename);
            }
        }        
    }
    
    private function getExpectedFileNamesFor(ExclusionList $exclusionList, $noOfFiles) {
        
        $expected = [];
        
        for ($i = 0; $i < $noOfFiles; $i++) {
            //i.e. <download_dir>/wv1-0.pdf
            $expected[] = $this->downloadDirectory . '/' . $exclusionList->dbPrefix . '-' . $i . '.' . $exclusionList->type;
        }
        
        return $expected;
    }
    
}