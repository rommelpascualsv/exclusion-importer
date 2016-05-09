<?php namespace App\Import\Lists;

use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class Wyoming extends ExclusionList
{

    public $dbPrefix = 'wy1';

    public $pdfToText = "java -Dfile.encoding=utf-8 -jar ../etc/tabula.jar -g -p all";

    public $uri = 'http://www.health.wyo.gov/Media.aspx?mediaId=18376';

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
        'npi'
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
    
    protected $npiColumnName = "npi";
    
    public $shouldHashListName = true;
    
    /**
     * Regular expression that identifies the NPI within a provider number string
     * Known formats are:
     * 1. 'NPI <NPI number>'
     * 2. 'NPI <NPI number>;'
     * 3. '<NPI number> NPI
     * @var string
     */
    private $npiRegEx = [
        '/(NPI\s?(1\d{9})\b\s?;?)|(\b(1\d{9})\s?NPI)/',
        '/1\d{9}/'
    ];
    
    private $providerRegex = [
        '/(NPI\s?(1\d{9})\b\s?;?)|(\b(1\d{9})\s?NPI)/',
        '/(^(,|;|\/)?\s?)|((,|;|\/)?\s?$)/' // Extra symbols at the start and end
    ];
    
    protected $providerNumberColumnName = "provider_number";
    
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    protected function parse()
    {
        $rows = preg_split('/(\r)?\n(\s+)?/', $this->data);
        
        $data = [];
        
        foreach ($rows as $row) {
            
            $row = trim($row);
            
            if (! $row || $this->isHeader($row)) {
                continue;
            }
            
            $columns = str_getcsv($row);
//             $columns[] = ''; // placeholder for NPI column value. Value will be derived in postProcess()
            
            if ($this->isContinuationOfPreviousRow($columns)) {
                // Rows without any exclusion date are assumed to just be continuations
                // of the data in the previous row. In this case, we should concatenate
                // the data of the current row with the data of the previous row
                $previousColumns = array_pop($data);
                
                $columns = $this->joinRowColumns($previousColumns, $columns);
            }
            
            $data[] = $columns;
        }
        
        $this->data = $data;
        
        //Some post-processing that can only be done once we have the fully
        //parsed data
        $this->data = array_map(function ($row){
            $row = array_map('trim', $row);
            return $this->handleRow($row);
            
        }, $this->data);
    }

    private function isHeader($row)
    {
        return stripos($row, 'Last Name') !== false;
    }

    /**
     * Returns true if the given row columns indicate that the row is just a continuation
     * of the previous row, false otherwise
     * @param array $columns the row data
     */
    private function isContinuationOfPreviousRow($columns)
    {
        $exclusionDate = $columns[$this->dateColumns['exclusion_date']];
        return empty(trim($exclusionDate));
    }
    
    /**
     * Appends the values of the given columns, i.e. columns2[0] is appended to
     * columns1[0], etc. This method assumes that both columns1 and columns2 are
     * the same size.
     * @param array $columns1 the array of column values to wich values from column2 will be appended
     * @param array $columns2 the array of column values to append to column1
     * @return string[] array of the combined values of columns1 and columns2
     */
    private function joinRowColumns($columns1, $columns2)
    {
        $results = [];
        
        for ($i = 0; $i < count($columns1); $i ++) {
            $results[] = $columns1[$i] . $columns2[$i];
        }
        
        return $results;
    }
    
    /**
     * Handles the data manipulation of an array record.
     *
     * @param array $row the array record
     * @return array $row the array record
     */
    private function handleRow($row)
    {
        $providerNoIndex = $this->getProviderNumberColumnIndex();
        
        // set npi number array
        $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $providerNoIndex, $this->npiRegEx));
         
        // set provider number
        $row = PNHelper::setProviderNumberValue($row, PNHelper::getProviderNumberValue($row, $providerNoIndex, $this->providerRegex), $providerNoIndex);
        
        return $row;
    }
}
