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
     * @param string name
     * @return array
     */
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
                array_shift($value);
            } else {
                $institution = $this->parseBusiness($value[0]);
                array_shift($value);
            }
            $data = array_merge($institution, $value);
        }

        return $data;
    }

    /**
     * @inherit parse
     */
    protected function parse()
    {
        $data = [];
        $rows = preg_split('/(\r)?\n(\s+)?/', $this->data);

        //row offset
        for ($i=0; $i < $this->retrieveOptions['offset']; $i++) {
            array_shift($rows);
        }

        foreach ($rows as $key => $value) {
            $this->business = '';
            $value = preg_replace('/[\r\n]+/', ' ', $value);
            $value = preg_replace('!\s+!', ' ', $value);
            $row = str_getcsv($value);
            trim($row[0]);

            foreach ($this->institutions as $ins) {
                if (strpos($row[0], $ins) !== false) {
                    $row[0] = str_replace($ins, '', trim($row[0]));
                    $this->business = str_replace('/', '', $ins);
                    break;
                }
            }

            $data[] = $this->override($row);
        }

        array_pop($data);
        $this->data = $data;
    }
}
