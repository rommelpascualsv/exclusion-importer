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

    public static function createZip($files, $destination = '', $overwrite = false)
    {
        // if the zip file already exists and overwrite is false, return false
        if (file_exists($destination) && ! $overwrite) {
            return false;
        }

        $valid_files = [];
        // if files were passed in...
        if (is_array($files)) {
            // cycle through each file
            foreach ($files as $file) {
                // make sure the file exists
                if (file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        // if we have good files...
        if (count($valid_files)) {
            // create the archive
            $zip = new \ZipArchive();
            if ($zip->open($destination, $overwrite ? \ZipArchive::OVERWRITE : \ZipArchive::CREATE) !== true) {
                return false;
            }
            // add the files
            foreach ($valid_files as $file) {
                $localName = pathinfo($file, PATHINFO_FILENAME) . '.' .pathinfo($file, PATHINFO_EXTENSION);
                $zip->addFile($file, $localName);
            }

            $zip->close();
            
            // check to make sure the file exists
            return file_exists($destination);
        } else {
            return false;
        }
    }
    
    /**
     * Deletes the files specified in $files if they are under $dir. The directory
     * and file paths must be absolute.
     * @param string $dir
     * @param string|array $files 
     */
    public static function deleteIfInDir($dir, $files)
    {
        if (! $files || ! $dir) {
            return;
        }
         
        try {
            if (! is_array($files)) {
                $files = [$files];
            }
    
            $filesToDelete = [];
    
            foreach ($files as $file) {
                if (strpos($file, $dir) === 0) {
                    $filesToDelete[] = $file;
                }
            }
             
            FileUtils::deleteFiles($filesToDelete);
    
        } catch (\Exception $e) {
            //quietly handle exceptions here
            info('Encountered an error while trying to delete files in directory ' . $dir . ' : ' . $e->getMessage());
        }
    
    }
    
}
