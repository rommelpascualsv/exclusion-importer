<?php namespace App\Import\Lists;

class WestVirginia extends ExclusionList
{
    /**
     * @var string
     */
    public $dbPrefix = 'wv2';

    /**
     * @var string
     */
    public $uri = 'https://s3.amazonaws.com/StreamlineVerify-Storage/exclusion-lists/west-virginia/wv2.xlsx';

    public $pdfToText = 'java -Dfile.encoding=utf-8 -jar ../etc/tabula.jar -p all -u -g -r'; //'pdftotext -layout -nopgbrk';

    public $type = 'pdf';

    /**
     * @var array
     */
    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    /**
     * @var array
     */
    public $fieldNames = [
        'npi_number',
        'full_name',
        'first_name',
        'middle_name',
        'last_name',
        'generation',
        'credentials',
        'provider_type',
        'city',
        'state',
        'exclusion_date',
        'reason_for_exclusion',
        'reinstatement_date',
        'reinstatement_reason'
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'npi_number',
        'full_name',
        'first_name',
        'middle_name',
        'last_name',
        'exclusion_date',
        'reinstatement_date',
    ];

    /**
     * @var array
     */
    public $dateColumns = [
        'exclusion_date' => 10,
        'reinstatement_date' => 12
    ];

    /**
     * @inherit preProcess
     */
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    /**
     * @param string csv
     * @return array
     */
    public function lineToaArray($string)
    {
        return preg_split('/(\r)?\n(\s+)?/', trim($string));

    }

    private function csvToArray($csv)
    {
        return str_getcsv($csv);
    }

    private function buildData($string)
    {
        // Convert new line to array
        $array = $this->lineToaArray($string);

        return array_map(function ($item) {
            //Each row contains csv convert it to array
            $row = $this->csvToArray($item);

            if (strpos($row[0], 'NPI') === false) {

                if (count($row) == 15) {
                    unset($row[11]);
                    $row = array_values($row);
                }

                foreach ($this->checkLastFirstName($row) as $value) {
                    if ($value) {
                        return $this->checkLastFirstName($row);
                    }
                }
            }

        }, $array);
    }

    private function sanitize(array $array)
    {
        $data = [];

        foreach ($array as $key => $value) {
            if ($value) {
                $data[] = $value;
            }
        }

        return $data;
    }


    private function checkLastFirstName(array $row)
    {
        if ($row[2] && $row[4]) {
            $row[1] = '';
        }

        return $row;
    }

    /**
     * Parse data if Entity or Individual
     * @return void data method
     */
    protected function parse()
    {
        $data = [];
        foreach ($this->data as $key => $value) {
            $data = array_merge($data, $this->sanitize($this->buildData($value)));
        }

        $this->data = $data;
    }
}
