<?php namespace App\Import\Lists;

class ProviderNumberHelper
{
    private static $npiRegex = [
        '/1\d{9}\b/' // Valid NPI numbers
    ];

    private static $providerRegex = [
        "/(1\d{9}(,|;|\/)?\s?)|((1\d{9})\b)/", // Valid NPI numbers with symbols
        "/(^(,|;|\/)?\s?)|((,|;|\/)?\s?$)/" // Extra symbols at the start and end
    ];

    private static $spacesRegex = "!\s+!";

    /**
     * Retrieves the npi value from the specified column index after execution
     * of the array regex that filters the original value. If the arrayRegex is null
     * or not provided, then it will use the default regex list.
     *
     * @param array $row the array containing the row values
     * @param int $sourceIndex the column index of the input value
     * @param string $arrayRegex the array containing the list of regex that will filter the source value
     * @return array $row the updated row
     */
    public static function getNpiValue($row, $sourceIndex, array $arrayRegex = null)
    {
        $regexToUse = $arrayRegex === null ? self::$npiRegex : $arrayRegex;

        // set the source to a temp variable
        $value = $row[$sourceIndex];

        // iterate array of regex since preg_match_all doesn't support array of patterns
        foreach ($regexToUse as $regex) {

            // extract npi number/s
            preg_match_all($regex, $value, $npi);

            // implode the matched values and set back the matches to the input variable for next regex iteration
            $value = implode(" ", $npi[0]);
        }

        return $npi[0];
    }

    /**
     * Sets the npi value by either inserting it at the end of the row if no index provided or
     * set it in the existing column.
     *
     * @param array $row the array containing the row values
     * @param string $npi the npi string
     * @param int $npiColumnIndex the column index of the npi value
     * @return array $row the updated row
     */
    public static function setNpiValue($row, $npi, $npiColumnIndex = null)
    {
        if ($npiColumnIndex === null) {
            $row[] = $npi;
        } else {
            $row[$npiColumnIndex] = $npi;
        }

        return $row;
    }

    /**
     * Retrieves the provider number value from the specified column index after execution
     * of the array regex that filters the original value. If the arrayRegex is null
     * or not provided, then it will use the default regex list.
     *
     * @param array $row the array containing the row values
     * @param int $sourceIndex the column index of the input value
     * @param string $arrayRegex the array containing the list of regex that will filter the source value
     * @return array $row the updated row
     */
    public static function getProviderNumberValue($row, $sourceIndex, array $arrayRegex = null)
    {
        $regexToUse = $arrayRegex === null ? self::$providerRegex : $arrayRegex;

        // remove valid npi numbers, symbols
        $providerNumber = preg_replace($regexToUse, "", trim($row[$sourceIndex]));

        // remove duplicate spaces
        $providerNumber = preg_replace(self::$spacesRegex, " ", trim($providerNumber));

        return $providerNumber;
    }

    /**
     * Sets the provider number value by either inserting it at the end of the row if no index provided or
     * set it in the existing column.
     *
     * @param array $row the array containing the row values
     * @param string $providerNumber the provider number string
     * @param int $providerNumberColumnIndex the column index of the provider number
     * @return array $row the updated row
     */
    public static function setProviderNumberValue($row, $providerNumber, $providerNumberColumnIndex = null)
    {
        if ($providerNumberColumnIndex === null) {
            $row[] = $providerNumber;
        } else {
            $row[$providerNumberColumnIndex] = $providerNumber;
        }

        return $row;
    }
}
