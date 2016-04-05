<?php namespace App\Import\Lists;

class Washington extends ExclusionList
{
    public $dbPrefix = 'wa1';

    public $pdfToText = 'java -jar ../etc/tabula.jar -p all -u -g -r'; //'pdftotext -layout -nopgbrk';

    public $uri = 'http://www.hca.wa.gov/medicaid/provider/documents/termination_exclusion.pdf';

    public $type = 'pdf';


    /**
     * @var field names
     */
    public $fieldNames = [
        'last_name',
        'first_name',
        'middle_etc',
        'entity',
        'provider_license',
        'npi_number',
        'termination_date',
        'termination_reason'
    ];

    /**
     * @var row offset
     */
    public $retrieveOptions = [
        'offset'    => 2
    ];

    public $hashColumns = [
        'last_name',
        'first_name',
        'entity',
        'provider_license',
        'npi_number',
        'termination_date'
    ];

    /**
     * @var institution special cases
     */
    private $institutions = [
        'Wheelchairs Plus',
        'AA Adult Family Home',
        'Our House Adult Family Home/',
        'Wheelchairs Plus',
        '/Fairwood Care'
    ];

    public $dateColumns = [
       'termination_date' => 6
    ];

    private $business;

    /**
     * @inherit preProcess
     */
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    /**
     * @param string
     * @return boolean
     */
    public function valueIsLastNameFirst($name)
    {
        return strpos($name, ', ') !== false;
    }

    /**
     * @param string
     * @return array
     */
    public function parseLastNameFirst($name)
    {
        $completeName = explode(', ', $name);
        $names[] = $completeName[0];

        if (isset($completeName[1])) {
            $firstMiddle = explode(' ', $completeName[1], 2);

            if (count($firstMiddle) == 1) {
                $firstMiddle[] = '';
            }

            $names = array_merge($names, $firstMiddle);
        }

        return $names;
    }

    /**
     * @param string
     * @return array
     */
    public function textNewLineToArray($string)
    {
        return preg_split('/(\r)?\n(\s+)?/', trim($string));
    }

    /**
     * @param string csv
     * @return array
     */
    public function csvToArray($string)
    {
        $string = preg_replace('/[\r\n]+/', ' ', $string);
        $string = preg_replace('!\s+!', ' ', $string);
        $array = str_getcsv($string);
        return array_map('trim', $array);
    }

    /**
     * @param string name
     * @return array
     */
    private function parseName($name)
    {
        $names = [];

        if ($this->valueIsLastNameFirst($name)) {
            return $this->parseLastNameFirst($name);
        }

        $names = explode(' ', $name);
        $names[] = '';

        return $names;
    }

    /**
     * @param string business
     * @return array
     */
    private function parseBusiness($business)
    {
        $name = ['', '', ''];
        $name[] = $business;
        return $name;
    }

    /**
     * @param array data rows
     * @return array
     */
    private function override(array $value)
    {
        $data = '';

        //if combination of business and name
        if ($this->business) {
            $name = $this->parseName($value[0]);
            $name[] = $this->business;
            $value = array_offset($value, 1);
            return array_merge($name, $value);
        }

        //if Name without business
        if ($this->valueIsLastNameFirst($value[0])) {
            $name = $this->parseName($value[0]);
            $name[] = '';
            $value = array_offset($value, 1);
            return array_merge($name, $value);
        }

        //if business w/o name
        $name = $this->parseBusiness($value[0]);
        $value = array_offset($value, 1);

        return array_merge($name, $value);
    }

    /**
     * @inherit parse
     */
    protected function parse()
    {
        $data = [];
        $rows = $this->textNewLineToArray($this->data);

        //array offset
        $rows = array_offset($rows, $this->retrieveOptions['offset']);

        foreach ($rows as $key => $value) {
            $this->business = '';
            // convert csv string to array
            $row = $this->csvToArray($value);

            // Check for combination of name and business
            foreach ($this->institutions as $ins) {
                if (strpos($row[0], $ins) !== false) {
                    $row[0] = str_replace($ins, '', $row[0]);
                    $this->business = str_replace('/', '', $ins);
                    break;
                }
            }

            $data[] = $this->override($row);
        }

        $this->data = $data;
    }
}
