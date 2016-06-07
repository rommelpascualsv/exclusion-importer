<?php 

namespace App\Utils;

class FileUtils
{
    /**
     * Compares the contents of two files to determine if they are equal or not.
     * 
     * @param string $file1 the path to the first file
     * @param string $file2 the path to the second file
     */  
    public static function contentEquals($file1, $file2)
    {
        if ($file1 == null && $file2 != null) {
            return false;
        }
        
        if ($file1 != null && $file2 == null) {
            return false;
        }
        
        if ($file1 == null && $file2 == null) {
            return true;
        }
        
        // Check if filesize is different
        if(filesize($file1) !== filesize($file2)) {
            return false;
        }
        
        // For zip comparison, delegate to zipContentEquals
        if (self::isZip($file1) && self::isZip($file2)) {
            return self::zipContentEquals($file1, $file2);
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
    
    public static function zipContentEquals($file1, $file2)
    {
        $zip1 = null; 
        $zip2 = null;

        try {
            
            $zip1 = new \ZipArchive();
            $res1 = $zip1->open($file1);
            
            $zip2 = new \ZipArchive();
            $res2 = $zip2->open($file2);
            
            if (! $res1 || ! $res2) {
                return false;
            }
            
            // not the same number of files
            if ($zip1->numFiles !== $zip2->numFiles) {
                return false;
            }
            
            // compare the file size and crc checksum of each file
            for ($i = 0; $i < $zip1->numFiles; $i++) {
                
                $statIndex1 = $zip1->statIndex($i);
                $statIndex2 = $zip2->statIndex($i);
                // Compare file sizes
                if ($statIndex1['size'] !== $statIndex2['size']) {
                    return false;
                }
                //Compare crc
                if ($statIndex1['crc'] !== $statIndex2['crc']) {
                    return false;
                }
                
            }
            
            return true;
            
        } finally {
            
            if ($zip1) $zip1->close();
            if ($zip2) $zip2->close();
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
            error('Encountered an error while trying to delete files in directory ' . $dir . ' : ' . $e->getMessage());
        }
    
    }
    
    public static function isZip($file)
    {
        $res = null;
        
        try {
            $res = zip_open($file);
            return is_resource($res);
        } finally {
            if (is_resource($res)) zip_close($res);
        }
    }
    
}
