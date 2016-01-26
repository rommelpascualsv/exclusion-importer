<?php namespace App\Import\Lists;


class Washington extends ExclusionList
{

    public $dbPrefix = 'wa1';


    public $pdfToText = "pdftotext -layout -nopgbrk";


    public $uri = "http://www.hca.wa.gov/medicaid/provider/documents/termination_exclusion.pdf";


    public $type = 'pdf';


    public $fieldNames = [
        'last_name',
        'first_name',
        'middle_etc',
        'entity',
        'provider_license',
        'termination_date',
        'termination_reason'
    ];


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset'    => 1
    ];


    public $hashColumns = [
        'last_name',
        'first_name',
        'entity',
        'provider_license',
        'termination_date'
    ];


    public $dateColumns = [
        'termination_date' => 5
    ];


    public function parse($text)
    {

        $rowDelimiter = '^^^^^';
        $columnDelimiter = '~~~~~';
        $blankDelimiter = '';

        //$regex = preg_replace('/\n(\s)?\d{1,3}/', "\n", $text);
        //$regex1 = preg_replace('/A\s{1,}B\s{1,}C\s{1,}D\s{1,}E\s{1,}/', $blankDelimiter, $regex);
        $regex1 = (preg_replace('/Plus[\s]+Medicaid\n/', $blankDelimiter, $text));
        $regex1A = str_replace('Mann, Michael DBA Wheelchairs', 'Mann DBA Wheelchairs Plus, Michael', $regex1);
        $regex1B= str_replace('Manditory Exclusion from', 'Manditory Exclusion from Medicaid ', $regex1A);

        $regex2 = (preg_replace('/Molnar, Laszlo/', $blankDelimiter, $regex1B));
        $regex2B= str_replace('AA Adult Family Home ', 'Molnar AA Adult Family Home, Laszlo ', $regex2);

        $regex3 = preg_replace('/^[\s\S\w]+Action[\s]+Exclusion[\s]+/', $blankDelimiter, $regex2B);

        $regex4 = str_replace('6/30/2014 Terminated by another state','6/30/2014' . $columnDelimiter . 'Terminated by another state', $regex3);

        $regex5 =  str_replace('4/26/2005 Licensed Revoked', '4/26/2005' . $columnDelimiter . 'Licensed Revoked', $regex4);
        $cleanData = preg_replace_callback('/(AP30004851|DE00005459|MD00015460|MD00037164|OP00000920|AP30003392|License |OIG |Terminate |Inactive )/', function($match){
            return '   ' . $match[0];
        }, $regex5);

        $regex6 = preg_replace('/(\r)?\n(\s+)?/', $rowDelimiter, $cleanData);
        $regex7 = preg_replace('/\s{3,}/', $columnDelimiter, $regex6);
        $regex8 = preg_replace('/\s{10}E\s{10}/', $blankDelimiter, $regex7);
        $regex9 = str_replace('MD28670 California 12/1/2005', 'MD28670 California'. $columnDelimiter . '12/1/2005', $regex8);
        $regex9b = str_replace('MD19106 Arizona 5/20/2007', 'MD19106 Arizona'. $columnDelimiter . '5/20/2007', $regex9);
        $regex10 = str_replace("6/30/2014 Terminated by CMS", "6/30/2014" . $columnDelimiter .  "Terminated by CMS", $regex9b);

        $rows = explode($rowDelimiter, $regex10);

        foreach ($rows as $row)
        {
            $rowArray = explode($columnDelimiter, $row);

            $firstMiddle = explode(', ', $rowArray[0], 2); //separate last name from rest of name

            if (isset($firstMiddle[1]))
            {
                $middle = explode(' ', $firstMiddle[1], 2); //separate first name

                if ( ! isset($middle[1])) //add blank if no middle name so won't act like entity
                    $middle[1] = ' ';

                array_splice($firstMiddle, 1, 2, $middle);
            }

            array_splice($rowArray, 0, 1, $firstMiddle);
            array_splice($rowArray, 3, 0, ''); //insert blank column for entities

            if (count($rowArray) < 6)
            {
                $entity = $rowArray; //get entity
                unset($entity[3]); //remove blank column
                array_splice($rowArray, 3, count($entity), $entity); //insert entity
                //set FML names to balnk
                $rowArray[0] = '';
                $rowArray[1] = '';
                $rowArray[2] = '';
            }

            $columns[] = $rowArray;
        }

        //check for empty arrays
        foreach ($columns as $key => $value)
        {
            if ( ! array_filter($value))
                unset($columns[$key]);
        }

        $this->data = $columns;

        return $this;
    }

}
