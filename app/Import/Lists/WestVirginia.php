<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class WestVirginia extends ExclusionList
{
    /**
     * @var string
     */
    public $dbPrefix = 'wv2';

    /**
     * The parser needs to work with multiple urls in this case
     *
     * @var string
     */
    public $uri = 'https://www.wvmmis.com/WV%20Medicaid%20Provider%20SanctionedExclusion/WV%20Medicaid%20Exclusions%20-%20March%20Posting.pdf,https://www.wvmmis.com/WV%20Medicaid%20Provider%20SanctionedExclusion/WV%20Medicaid%20Provider%20Term%20-%20Exclusion%20Info%20Other%20-%20March%202016%20Posting.pdf,https://www.wvmmis.com/WV%20Medicaid%20Provider%20SanctionedExclusion/WV%20Medicaid%20Terminations%20-%20March%202016%20Posting.pdf';

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
        'reinstatement_reason',
        'provider_number'	
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

    public $shouldHashListName = true;
    
    protected $npiColumnName = "npi_number";
    
    /**
     * @inherit preProcess
     */
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    /**
     * @param string $string
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

    /**
     * Remove header of the array
     * 
     * @param array $array
     * @return array the array data without the header
     */
    private function removeHeader(array $array)
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
            $data = array_merge($data, $this->removeHeader($this->buildData($value)));
        }
        
        $this->data = array_map(function ($row) {
        	
            $npiColumnIndex = $this->getNpiColumnIndex();
            // set provider number
            $row = PNHelper::setProviderNumberValue($row, PNHelper::getProviderNumberValue($row, $npiColumnIndex));
            
            // set npi number array
            $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $npiColumnIndex), $npiColumnIndex);
            
            return $row;
            
        }, $data);
    }
}
