<?php namespace App\Import\Lists;

use App\Import\Lists\ProviderNumberHelper as PNHelper;

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
            
            $row = array_map('trim', $row);
            
            $npiColumnIndex = $this->getNpiColumnIndex();
            
            $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $npiColumnIndex), $npiColumnIndex);
            
            $data[] = $row;
        }
    
        $this->data = $data;
    }
    
    private function isHeader($row) {
        return (stripos($row[0], 'Provider ID') !== false || stripos($row[1], 'Provider NPI') !== false);
    }
    
}
