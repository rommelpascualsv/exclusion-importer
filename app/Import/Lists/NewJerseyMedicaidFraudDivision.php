<?php
namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class NewJerseyMedicaidFraudDivision extends ExclusionList
{
    public $dbPrefix = 'njmfdper';

    public $pdfToText = "java -Dfile.encoding=utf-8 -jar /vagrant/etc/tabula.jar -g -p all -r";

    public $uri = "http://nj.gov/comptroller/divisions/medicaid/disqualified/monthly_exclusion_report.pdf";

    public $type = 'pdf';

    public $fieldNames = [
        'provider_name',
        'title',
        'date_of_birth',
        'npi_number',
        'street',
        'city',
        'state',
        'zip',
        'action',
        'effective_date',
        'expiration_date'
    ];

    public $hashColumns = [
        'provider_name',
        'title',
        'date_of_birth',
        'npi_number',
        'street',
        'city',
        'state',
        'zip',
        'effective_date',
        'expiration_date'
    ];

    private $listOfExcludedRows = [
        'PROVIDER NAME,TITLE,"DATE OFBIRTH",NPI NUMBER,STREET,CITY,"STATE",ZIP,ACTION,"EFFECTIVEDATE","EXPIRATIONDATE",',
        'PROVIDER NAME,TITLE,"DATE OFBIRTH",NPI NUMBER,STREET,CITY,"STATE",ZIP,ACTION,"EFFECTIVEDATE","EXPIRATIONDATE"'
    ];

    public $dateColumns = [
        'date_of_birth' => 2,
        'effective_date' => 9,
        'expiration_date' => 10
    ];

    protected $npiColumnName = "npi_number";

    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    public function parse()
    {
        $rows = preg_split('/(\r)?\n(\s+)?/', $this->data);
        $data = [];
        foreach ($rows as $value) {
            $value = preg_replace('/\r/', '', $value);
            if (empty($value) || $this->isExcludedRow($value)) {
                continue;
            }
            $row = str_getcsv($value);

            // set provider number
            $row = PNHelper::setProviderNumberValue($row,
                PNHelper::getProviderNumberValue($row, $this->getNpiColumnIndex()));

            // set npi number array
            $row = PNHelper::setNpiValue($row,
                PNHelper::getNpiValue($row, $this->getNpiColumnIndex()), $this->getNpiColumnIndex());
            array_push($data, $row);
        }
        $this->data = $data;
    }

    private function isExcludedRow($value)
    {
        foreach ($this->listOfExcludedRows as $excludedRow) {
            if ($value == $excludedRow) {
                return true;
            }
        }
        return false;
    }

}
