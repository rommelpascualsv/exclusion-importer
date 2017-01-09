<?php
namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;
use App\Import\Entities\IndividualName;
use \App\Utils\AliasSeparatorUtil;
use App\Import\Entities\IndividualNameParser;

class NewJerseyMedicaidFraudDivision extends ExclusionList
{
    public $dbPrefix = 'njmfdper';

    public $pdfToText = "java -Dfile.encoding=utf-8 -jar /vagrant/etc/tabula.jar -g -p all -r -u";

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
        'expiration_date',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'aka_dba'
    ];

    public $hashColumns = [
        'provider_name',
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
        'PROVIDER NAME,TITLE,"DATE OF BIRTH",NPI NUMBER,STREET,CITY,"STA TE",ZIP,ACTION,"EFFECTIVE DATE","EXPIRATION DATE",',
        'PROVIDER NAME,TITLE,"DATE OF BIRTH",NPI NUMBER,STREET,CITY,"STA TE",ZIP,ACTION,"EFFECTIVE DATE","EXPIRATION DATE"'
    ];

    public $dateColumns = [
        'date_of_birth' => 2,
        'effective_date' => 9,
        'expiration_date' => 10
    ];

    protected $npiColumnName = "npi_number";

    private $businessTypeAbbreviation = ['INC.', 'LLC.'];

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
            $value = preg_replace('/\r/', ' ', $value);
            if (empty($value) || $this->isExcludedRow($value)) {
                continue;
            }
            $row = str_getcsv($value);

            $providerName = $row[0];
            if ($this->isBusinessName($providerName)) {
                // set Provider Name
                $row[0] = AliasSeparatorUtil::removeAliases($providerName);
                $individualName = new IndividualName();
            } else {
                // set Individual Name
                $individualName = IndividualNameParser::parseName($providerName);
                $row[0] = null;
            }

            $row[11] = $individualName->getFirstName();
            $row[12] = $individualName->getMiddleName();
            $row[13] = $individualName->getLastName();
            $row[14] = $individualName->getSuffix();

            // set Alias
            $row[15] = AliasSeparatorUtil::getAliases($providerName);
            if (empty($row[15])) {
                $row[15] = NULL;
            }

            // set Provider Number
            $row = PNHelper::setProviderNumberValue($row,
                PNHelper::getProviderNumberValue($row, $this->getNpiColumnIndex()));

            // set Npi Number Array
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

    public function isBusinessName($name) {
        $wordParts = explode(',', $name);

        if (! (count($wordParts) > 1)) {
            return true;
        }

        $firstPart = explode(' ', head($wordParts));
        $lastPart = explode(' ', last($wordParts));
        if (count($firstPart) > 1 &&
            !(in_array(strtolower('AKA'), array_map('strtolower', $firstPart))) &&
            in_array(strtolower(last($lastPart)), array_map('strtolower', $this->businessTypeAbbreviation))) {
            return true;
        }
    }


}
