<?php 

namespace App\Utils;

class FileUtils
{
    /**
     * Compares the contents of two files to determine if they are equal or not
     * @param string $file1 the path to the first file
     * @param string $file2 the path to the second file
     */  
    public static function contentEquals($file1, $file2)
    {
        // Check if filesize is different
        if(filesize($file1) !== filesize($file2)) {
            return false;
        }
        
        $file1Resource = null;
        $file2Resource = null;
        
        try {
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
            
            return $result;
            
        } finally {
            if ($file1Resource) fclose($file1Resource);
            if ($file2Resource) fclose($file2Resource);
        }
    }
    
    /**
     * Deletes all the files specified in the filenames array.
     * @param array $filenames array of file paths to delete
     */
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
