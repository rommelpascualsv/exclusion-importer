<?php namespace App\Import\Lists;

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
    
    public $npiColumnName = 'npi';
    
    public $shouldHashListName = true;
    
    /**
     * Regular expression that identifies the NPI within a provider number string
     * Known formats are:
     * 1. 'NPI <NPI number>'
     * 2. 'NPI <NPI number>;'
     * 3. '<NPI number> NPI
     * @var string
     */
    private $npiRegEx = '/NPI\s?(1\d{9})\b\s?;?|\b(1\d{9})\s?NPI/';
    
    private $npiColumnIndex;
    private $providerNumberColumnIndex;
    
    public function __construct()
    {
        parent::__construct();
        $this->npiColumnIndex = array_search($this->npiColumnName, $this->fieldNames);
        $this->providerNumberColumnIndex = array_search('provider_number', $this->fieldNames);
    }    
    
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
            $columns[] = ''; // placeholder for NPI column value. Value will be derived in postProcess()
            
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
        $this->data = array_map(function ($columns){
            
            $this->enrichData($columns);
            
            $columns = $this->trimData($columns);
            
            return $columns;
            
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
     * Enriches the passed row column data with derived data, such as NPI numbers.
     * @param array $columns the row column data
     */
    private function enrichData(&$columns)
    {

        $providerNumberColumnIndex = $this->providerNumberColumnIndex;
        
        $npi = $this->parseNPI($columns[$providerNumberColumnIndex]);
        
        $columns[$this->npiColumnIndex] = $npi;
        
        if (! empty($npi)) {
            $columns[$providerNumberColumnIndex] = $this->trimNPI($columns[$providerNumberColumnIndex]);        }
                  
    }
    
    /**
     * Returns an array of NPI number(s) extracted from the given provider number,
     * or null if there are no NPI numbers in the provider number 
     * @param array $providerNumber the provider number from which to extract the
     * NPI number(s)
     * @return array array of NPI numbers contained by the provider number
     */
    private function parseNPI($providerNumber)
    {
        $providerNumber = trim($providerNumber);
    
        if (! $providerNumber) {
            return null;    
        }
    
        preg_match($this->npiRegEx, $providerNumber, $matches);
        
        if (empty($matches)) {
            return null;
        }
        
        $npi = [];
        
        // Start from matches[1], since we do not want the text that matched the full pattern (which is matches[0]),
        // since we only want to get the capture groups that contain only the NPI number
        for ($i = 1; $i < count($matches); $i++) {
            
            if ($matches[$i]) {
                $npi[] = $matches[$i];
            }
        }
    
        return $npi;
    }
    
    /**
     * Removes all NPI numbers (along with their prefixes/suffixes) from the given
     * provider number and returns the new provider number
     * @param unknown $providerNumber the provider number whose NPI data should
     * be removed
     * @return string the new provider number without the NPI data
     */
    private function trimNPI($providerNumber)
    {
        return preg_replace($this->npiRegEx, '', $providerNumber);
    }
    
    /**
     * Trims all whitespaces from each of the elements in columns and returns an
     * array containing the trimmed column data
     * @param array $columns the row column data
     * @return array array containing the trimmed column data
     */
    private function trimData($columns)
    {
        return array_map('trim',$columns);
    }
}
