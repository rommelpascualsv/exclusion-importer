<?php

namespace App\Import\Lists;

class HashUtils
{

    public static function generateHash(array $record, ExclusionList $exclusionList)
    {
        if (empty($exclusionList->hashColumns)) {
            $exclusionList->hashColumns = $exclusionList->fieldNames;
        }
        
        $hashData = [];
        
        foreach ($record as $key => $value) {
            if (in_array($key, $exclusionList->hashColumns)) {
                $hashData[] = $value;
            }
        }
        
        $string = preg_replace("/[^A-Za-z0-9]/", '', trim(strtoupper(implode('', $hashData))));
        
        //adds the exclusion list prefix to the hash to avoid having identical hashes in different lists
        if ($exclusionList->shouldHashListName) {
            $listName = trim(strtoupper($exclusionList->dbPrefix));
        
            $string .= $listName;
        }

        return md5($string);
    }
}
