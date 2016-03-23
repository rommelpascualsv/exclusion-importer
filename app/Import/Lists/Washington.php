<?php namespace App\Import\Lists;

class Washington extends ExclusionList
{
    public $dbPrefix = 'wa1';

    public $pdfToText = 'java -jar ../etc/tabula.jar -p all -u -g -r'; //'pdftotext -layout -nopgbrk';

    public $uri = 'http://www.hca.wa.gov/medicaid/provider/documents/termination_exclusion.pdf';

    public $type = 'pdf';

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

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset'    => 1
    ];

    public $hashColumns = [
        'last_name',
        'first_name',
        'entity',
        'provider_license',
        'npi_number',
        'termination_date'
    ];

    public $dateColumns = [
        'termination_date' => 5
    ];

    private $institutions = [
        'Wheelchairs Plus',
        'Adult Family Home',
        'Our House Adult Family Home/',
        'Wheelchairs Plus',
        '/Fairwood Care'
    ];

    private $bussiness;

    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    private function parseName($name)
    {
        $names = [];
        if (strpos($name, ', ') !== false) {
            $completeName = explode(', ', $name);
            $names[] = $completeName[0];

            if (isset($completeName[1])) {
                $firstMiddle = explode(' ', $completeName[1], 2);

                if (count($firstMiddle) == 1) {
                    $firstMiddle[] = '';
                }

                $names = array_merge($names, $firstMiddle);
            }
        } else {
            $names = explode(' ', $name);
            $names[] = '';
        }

        $names[] = '';

        return $names;
    }

    private function parseBusiness($business)
    {
        $name = ['', '', ''];
        $name[] = $bussiness;
        return $name;
    }

    private function override($value)
    {
        $data = '';
        $institution = '';

        if (strpos($value[0], ', ') !== false) {
            $name = $this->parseName($value[0]);
            array_shift($value);
            $row = array_merge($name, $value);
            if ($this->business) {
                $row[3] = $this->business;
            }
            $data = $row;
        } else {
            if ($this->business) {
                $institution = $this->parseName($value[0]);
                $institution[3] = $this->business;
            } else {
                $institution = $this->parseBusiness($value[0]);
                array_shift($value);
                array_shift($value);
            }
            $data = array_merge($institution, $value);
        }

        return $data;
    }

    protected function parse()
    {
        $data = [];
        $rows = preg_split('/(\r)?\n(\s+)?/', $this->data);
        array_shift($rows);
        array_shift($rows);

        foreach ($rows as $key => $value) {
            $this->bussiness = '';
            $value = preg_replace('/[\r\n]+/', ' ', $value);
            $value = preg_replace('!\s+!', ' ', $value);
            $row = str_getcsv($value);

            foreach ($this->institutions as $ins) {
                if (strpos($row[0], $ins) !== false) {
                    $row[0] = str_replace($ins, '', $row[0]);
                    $this->bussiness = $ins;
                    break;
                }
            }

            $data[] = $this->override($row);
        }
        print_r($data);
        exit;
        $this->data = $data;
    }
}
