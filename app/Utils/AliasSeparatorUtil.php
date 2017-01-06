<?php
namespace App\Utils;

class AliasSeparatorUtil
{

    /**
     * This will return the AKAs and DBAs from the provided string.
     *
     * @param $toProcess e.g. "BYERS, RAYMOND AKA FAYE BYERS DBA HATIM"
     * @return json object e.g. ([0] => FAYE BYERS, [1] => HATIM)
     *         otherwise empty string.
     */
    public static function getAliases($toProcess)
    {
        if (empty(trim($toProcess))) {
            return '';
        }

        // remove parentheses
        $noParentheses = preg_replace('/\(|\)/', '', $toProcess);

        // separate non-alias and aliases
        $pattern = '/(?i)(AKA|DBA)\s?[:;]?\s?\W/';
        $split = preg_split($pattern, $noParentheses);

        // remove whitespace
        $trimmed = array_map('trim', $split);

        // get aliases
        $processed = array_slice($trimmed, 1);

        if (empty($processed)) {
            return '';
        } else {
            return json_encode($processed);
        }
    }

    /**
     * This will remove the AKAs and DBAs from the provided string.
     *
     * @param $toProcess e.g. "BYERS, RAYMOND AKA FAYE BYERS DBA HATIM"
     * @return string e.g. "BYERS, RAYMOND"
     */
    public static function removeAliases($toProcess)
    {
        if (empty(trim($toProcess))) {
            return '';
        }

        // remove parentheses
        $noParentheses = preg_replace('/\(|\)/', '', $toProcess);

        // separate non-alias and aliases
        $pattern = '/(?i)(AKA|DBA)\s?[:;]?\s?\W/';
        $split = preg_split($pattern, $noParentheses);

        // remove whitespace
        $trimmed = array_map('trim', $split);

        return implode('', array_slice($trimmed, 0, 1));
    }

}