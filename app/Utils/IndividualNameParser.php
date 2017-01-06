<?php namespace App\Utils;

use App\Models\IndividualName;
use App\Models\PersonName;

class IndividualNameParser
{

    private static $nameSuffixes = [
        'Jr.'  => 'Jr.',
        'Jr'   => 'Jr',
        'Sr.'  => 'Sr.',
        'Sr'   => 'Sr',
        'I'    => 'I',
        'II'   => 'II',
        'III'  => 'III',
        'IV'   => 'IV',
        'V'    => 'V',
        'VI'   => 'VI',
        'VII'  => 'VII',
        'VIII' => 'VIII',
        'IX'   => 'IX',
        'X'    => 'X'
    ];

    /**
     * Parses the first name, middle name, last name and suffix from the given value.
     * This assumes that the given value is formatted as <last name> <suffix>, <first name> <middle name>
     *
     * @param $name
     * @return IndividualName
     */
    public static function parseName($name)
    {
        $individualName = new IndividualName();

        $name = trim($name);
        if (empty($name)) {
            return $individualName;
        }

        $splitName = explode(',', $name, 2);

        $firstPart = explode(' ', $splitName[0]);
        // Find suffix
        $suffix = self::getSuffix(end($firstPart));
        $individualName->setSuffix($suffix);

        // Find last name
        $suffixIdx = array_search($suffix, $firstPart);
        if (! empty(trim($suffixIdx))) {
            unset($firstPart[$suffixIdx]);
        }
        $individualName->setLastName(implode(' ', $firstPart));


        $secondPart = explode(' ', trim($splitName[1]));
        // Find middle name
        $middleNameIdx = array_search(end($secondPart), $secondPart);
        if ($middleNameIdx > 0) {
            $individualName->setMiddleName($secondPart[$middleNameIdx]);
            unset($secondPart[$middleNameIdx]);
        }

        // Find first name
        $individualName->setFirstName(implode(' ', $secondPart));

        return $individualName;
    }

    private static function getSuffix($name)
    {
        $key = trim(array_search(strtolower($name), array_map('strtolower',self::$nameSuffixes)));
        if (empty(trim($key))) {
            return null;
        }
        return $name;
    }

}
