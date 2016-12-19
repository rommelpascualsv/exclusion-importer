<?php
namespace App\Utils;

class AliasSeparatorUtil
{

    /**
     * This will return the AKAs and DBAs from the provided string.
     *
     * @param $toProcess e.g. "BYERS, RAYMOND AKA FAYE BYERS DBA HATIM"
     * @return json object e.g. ([0] => FAYE BYERS, [1] => HATIM)
     */
    public static function getAliases($toProcess)
    {
        $processed = [];
        if ( ! empty(trim($toProcess))) {
            $pattern = '/(?i)(AKA|DBA)[\s]?[:]?[;]?[\s]?\W/';
            $split = preg_split($pattern, $toProcess);
            $trimmed = array_map('trim', $split);
            $processed = array_slice($trimmed, 1);
        }
        return json_encode($processed);
    }

}
