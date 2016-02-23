<?php namespace App\Import\Lists;


class Wyoming extends ExclusionList
{

    public $dbPrefix = 'wy1';


    public $pdfToText = "pdftotext -layout -nopgbrk";


    public $uri = 'http://www.health.wyo.gov/Media.aspx?mediaId=18045';


    public $type = 'pdf';


    public $fieldNames = [
        'last_name',
        'first_name',
        'business_name',
        'provider_type',
        'provider_number',
        'city',
        'state',
        'exclusion_date',
        'additional_info_1',
        'additional_info_2'
    ];


    public $retrieveOptions = [
        'headerRow' => 0,
        'offset'    => 1
    ];


    public $hashColumns = [
        'last_name',
        'first_name',
        'business_name',
        'provider_number',
        'exclusion_date'
    ];


    public $dateColumns = [
        'exclusion_date' => 7
    ];

    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    protected function parse()
    {
        $rowDelimiter = '^^^^^';
        $columnDelimiter = '~~~~~';

        $cleandata = preg_replace('/Registered Professional Nurs RN24869/', 'Registered Professional Nurs   RN24869', $this->data);
        $cleandata1 = preg_replace('/1129821 Denver/', '1129821   Denver', $cleandata);
        $cleandata2 = preg_replace('/Ambulance Company - Emer N\/A/', 'Ambulance Company - Emer   N/A', $cleandata1);
        $cleandata3 = preg_replace('/WY6604A/', 'WY6604A  N/A', $cleandata2);
        $cleandata4 = str_replace('Grooman (Martinez)', 'Grooman(Martinez)       ', $cleandata3);

        $regex = preg_replace('/Wyoming Exclusion list[\w\s]+Exclusion Dat\n\n/', '', $cleandata4);
        $regex1 = preg_replace('/WYLicense#4316A/' , '' , $regex);
        $regex2 = str_replace('104091000', '104091000 WYLicense#4316A', $regex1);
        $regex3 = str_replace('1087711 00 WY Prov Cheyenne', '1087711 00 WY Prov    Cheyenne', $regex2);
        $regex4 = preg_replace('/\n\n\n\n\n[\w\s,]+\n/', '', $regex3);
        $regex5 = preg_replace('/\n\n\n[\w\s,]+Exclusion Dat/', '', $regex4);
        $regex6 = preg_replace('/\n\n/', $rowDelimiter, $regex5);
        $regex7 = preg_replace('/\n/', $rowDelimiter, $regex6);
        $regex8 = preg_replace('/\s{2,}/', $columnDelimiter, $regex7);
        $regex9 = str_replace('6/18/2009', '6/18/2009' . $rowDelimiter, $regex8);
        $regex10 = str_replace('8/20/2015', '8/20/2015' . $rowDelimiter, $regex9);


        $rows = explode($rowDelimiter, $regex10);


        $valid = -1;
        $validRow = 0;

        $columns = [];

        foreach ($rows as $row)
        {
            $valid++;

            $rowArray = explode($columnDelimiter, $row);
            array_shift($rowArray);

            if (count($rowArray) == 7){
                array_splice($rowArray, 2, 0, '');
            }
            if (count($rowArray) == 6){
                array_splice($rowArray, 0, 0, '');
                array_splice($rowArray, 0, 0, '');
            }
            if (count($rowArray) == 9){
                array_splice($rowArray, 8, 0, '');
            }

            if (count($rowArray) == 8){
                array_splice($rowArray, 8, 0, '');
                array_splice($rowArray, 8, 0, '');
            }

            if (count($rowArray) == 3){
                array_push($rowArray, '', '', '', '', '', '', '');
            }

            if (count($rowArray) < 4)
            {
                $valid--;
                //$columns[$validRow] = array_merge($columns[$validRow],$rowArray);
                array_splice($columns[$validRow], 8, count($rowArray),$rowArray);
                continue;
            }

            $validRow = $valid;



            $columns[] = $rowArray;

        }

        $this->data = $columns;
    }

}
