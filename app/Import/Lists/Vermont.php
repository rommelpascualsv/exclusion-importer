<?php namespace App\Import\Lists;

class Vermont extends ExclusionList
{
    public $dbPrefix = 'vt1';
    
    public $type = 'xlsx';    
    
    public $hashColumns = [
        'provider_id',
        'npi',
        'full_name',
        'city',
        'state',
        'status_effective_date',
        'status_end_date'
    ];
    
    public $dateColumns = [
        'status_effective_date' => 7,
        'status_end_date' => 8  
    ];
    
    public $fieldNames = [
        'provider_id',
        'npi',
        'full_name',
        'city',
        'state',
        'status_code',
        'status_code_desc',
        'status_effective_date',
        'status_end_date',
        'provider_type'
    ];
    
    public $shouldHashListName = true;
    
    protected $npiColumnName = 'npi';
    
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }
    
    protected function parse()
    {
        
        $data = [];
    
        foreach ($this->data as $row) {
    
            if (! $row || $this->isHeader($row)) {
                continue;
            }

            $providerId = trim($row[0]);
            $npi = trim($row[1]);
            $fullName = trim($row[2]);
            $city = trim($row[3]);
            $state = trim($row[4]);
            $statusCode = trim($row[5]);
            $statusDesc = trim($row[6]);
            $statusEffectiveDate = trim($row[7]);
            $statusEndDate = trim($row[8]);
            $providerType = trim($row[9]);
    
            $npi = [$npi]; //Downstream processing expects NPI to be an array, so we wrap it in an array
    
            $data[] = [
                $providerId,
                $npi,
                $fullName,
                $city,
                $state,
                $statusCode,
                $statusDesc,
                $statusEffectiveDate,
                $statusEndDate,
                $providerType
            ];
        }
    
        $this->data = $data;
    }
    
    private function isHeader($row) {
        return (stripos($row[0], 'Provider ID') !== false || stripos($row[1], 'Provider NPI') !== false);
    }
    
}
