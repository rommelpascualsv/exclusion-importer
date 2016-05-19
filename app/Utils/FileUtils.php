<?php 

namespace App\Utils;

class FileUtils
{
    public static function contentEquals($file1, $file2)
    {
        // Check if filesize is different
        if(filesize($file1) !== filesize($file2)) {
            return false;
        }
    
        // Check if content is different
        $file1Resource = fopen($file1, 'rb');
        $file2Resource = fopen($file2, 'rb');
    
        $result = true;
    
        while(!feof($file1Resource) && !feof($file2Resource)) {
    
            if(fread($file1Resource, 8192) != fread($file2Resource, 8192)) {
                $result = false;
                break;
            }
        }
    
        if(feof($file1Resource) !== feof($file2Resource)) {
            $result = false;
        }
    
        fclose($file1Resource);
        fclose($file2Resource);
    
        return $result;
    }
    
    public static function deleteFiles($filenames)
    {
        if (! $filenames) {
            return;
        }
        
        foreach ($filenames as $filename) {
            if (file_exists($filename)) {
                unlink($filename);
            }
        }        
    }
}
