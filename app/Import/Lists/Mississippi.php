<?php namespace App\Import\Lists;


class Mississippi extends ExclusionList
{

    public $dbPrefix = 'ms1';


    public $pdfToText = "pdftotext -layout -enc UTF-8";


    public $uri = "http://www.medicaid.ms.gov/wp-content/uploads/2014/03/SanctionedProviderList.pdf";


    public $fieldNames = [
        'entity_1',
        'entity_2',
        'address',
        'address_2',
        'specialty',
        'exclusion_from_date',
        'exclusion_to_date'
    ];


    public $hashColumns = [
        'entity_1',
        'entity_2',
        'address',
        'address_2',
        'exclusion_from_date',
    ];


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];


    public $dateColumns = [
        'exclusion_from_date' => 5
    ];


    public function parse($text)
    {
        $rowDelimiter = '^^^^^';
        $columnDelimiter = '~~~~~';
        $blankStatement = '';

        $sanitizedData = preg_replace('/\)\\nCassandra/', ")\n\nCassandra" , $text);
        $sanitizedData2 = preg_replace('/514 Woodrow Wilson Ave., Suite C/', "514 Woodrow Wilson Ave." , $sanitizedData);

        $regex = preg_replace('/[\s\S]+EXCLUSION PERIOD/', $blankStatement, $sanitizedData2);
        $regex1 = preg_replace('/\n\f/', $rowDelimiter, $regex);
        $regex2 = preg_replace('/\n\n/', $rowDelimiter, $regex1);
        $regex3 = preg_replace('/–/', '-', $regex2); //the dash being replaced is (alt + 0150) on the numpad
        $regex4 = preg_replace('/\n/', $columnDelimiter, $regex3);

        $rows = explode($rowDelimiter, $regex4);

        array_shift($rows);

        $dateRegex = '/\w+\s\d{1,2}\,\s\d{4}.+$/';

        foreach ($rows as $row) {

            $rowArray = explode($columnDelimiter, $row);

            //pull out date and split it to 2 separate columns at the end of array
            preg_match($dateRegex, $rowArray[0], $date);

            if (count($date) > 0) {
                $toDate = explode(' - ', $date[0]);

                $rowArray = array_merge($rowArray, $toDate);

                if (array_key_exists(0, $rowArray))
                    $rowArray[0] = trim(preg_replace($dateRegex, '', $rowArray[0]));
            }

            if (count($rowArray) < 7) {

                array_splice($rowArray, 1, 0, '');//blank entity 2 column if none available
            }

            $anotherRegex = '/Specialty - /';

            if (array_key_exists(4, $rowArray))
                $rowArray[4] = preg_replace($anotherRegex, '', $rowArray[4]);

            if (array_key_exists(2, $rowArray))
            {
                if (preg_match('/Ste/', $rowArray[2]) || preg_match('/STE/', $rowArray[2]) || preg_match('/Suite/', $rowArray[2]))
                {

                    $address1 = $rowArray[1];
                    $rowArray[2] = $address1 . ' ' . $rowArray[2];
                    $rowArray[1] = '';
                }
            }

            $columns[] = $rowArray;
        }

        foreach ($columns as $key => $value) {

            if (!array_filter($value))
                unset($columns[$key]);
        }

        $this->data = $columns;

        return $this;
    }

}
