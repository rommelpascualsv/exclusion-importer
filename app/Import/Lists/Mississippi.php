<?php namespace App\Import\Lists;

class Mississippi extends ExclusionList
{
    public $dbPrefix = 'ms1';

    public $uri = "https://medicaid.ms.gov/wp-content/uploads/2015/12/SanctionedProvidersList.xlsx";

    public $type = 'xlsx';

    public $fieldNames = [
        'last_name',
        'first_name',
        'middle_name',
        'entity_name',
        'dba',
        'address',
        'address_2',
        'specialty',
        'exclusion_from_date',
        'exclusion_to_date',
        'npi',
        'termination_reason',
        'sanction_type'
    ];

    public $hashColumns = [
        'last_name',
        'first_name',
        'middle_name',
        'entity_name',
        'dba',
        'exclusion_from_date',
        'exclusion_to_date',
        'npi'
    ];

    public $dateColumns = [
        'exclusion_from_date' => 8
    ];
    
    protected $npiColumnName = 'npi';
    
    public $shouldHashListName = true;
    
    protected $sanctionTypes = [
        'MFCU' => 'Medicaid Fraud Control Unit',
        'DOM'  => 'Division of Medicaid',
        'ME'   => 'Medicare Exclusion',
        'F'    => 'Individual or Entity Convicted of Fraud',
        'LB'   => 'Licensing Board',
        'OIG'  => 'OIG Exclusion',
        'CR'   => 'Certification Revoked'    
    ];
    
    protected $companySuffixRegex = '/\s*\b(Inc|LLC|PLLC|Co|Ltd|LC|LP|LLP|LLLP|Corp|PC)\.?\s*$/';
    
    protected $ownerRegex = '/(.*)-\s*Owners*\s*$/';
    
    private $multiTokenRegEx = '/(.*)\s{3,}(.*)/'; //Tokens separated by 3 or more spaces 
    
    private $dbaRegEx = '/dba\s+(.*)/';
    
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    protected function parse()
    {
        if (empty($this->data)) {
            return;
        }
        
        $data = [];
        
        $rows = $this->trimHeaderRows();
        
        foreach ($rows as $row) {
            
            $providerNameTokens = array_pad($this->splitTokens($row[1]), 2, '');
            
            //DBA Parsing
            $dba = $this->parseDBA($providerNameTokens);
            
            if (! empty($dba)) {
                $providerNameTokens = $this->trimDBA($dba, $providerNameTokens);
            }
            
            //First, Last, Middle, Entity Name Parsing
            $nameTokens = $this->parseNames($providerNameTokens);
            
            $addressTokens = array_pad($this->splitTokens($row[2]), 2, '');
            $npi = $this->parseNPI($row[3]);
            $specialty = trim($row[4]);
            $exclusionFromDate = trim($row[5]);
            $exclusionToDate = trim($this->parseExclusionToDate($row[6]));
            $terminationReason = trim($row[7]);
            $sanctionType = $this->parseSanctionType($row[8]);
            
            $data[] = [
                $nameTokens['lastName'],
                $nameTokens['firstName'],
                $nameTokens['middleName'],
                $nameTokens['entityName'],
                implode(', ', $dba),
                $addressTokens[0], 
                $addressTokens[1], 
                $specialty,        
                $exclusionFromDate,
                $exclusionToDate,  
                $npi,              
                $terminationReason, 
                $sanctionType       
            ];
        }

        $this->data = $data;
    }
    
    /**
     * Removes the header lines from the parsed Excel data, returning only
     * the portion that contains the exclusion list data
     */
    private function trimHeaderRows()
    {
        $headerOffset = -1;
        
        for ($i = 0; $i < count($this->data); $i++) {
            
            $row = implode(' ', $this->data[$i]);
            
            //Find the header row
            if (strpos($row, 'Provider Name') !== false) {
                $headerOffset = $i + 1;
                break;
            }
        }
        
        return $headerOffset === -1 ? $this->data : array_slice($this->data, $headerOffset);
    } 
    
    /**
     * Splits a string into tokens. Any string separated by 3 or more spaces from
     * its neighboring string is stored as a separate token in the resulting array 
     * @param string $src the string to tokenize
     * @return string[]
     */
    private function splitTokens($src)
    {
        preg_match($this->multiTokenRegEx, $src, $matches);
        
        $tokens = [];
        
        if (! empty($matches)) {
            
            for ($i = 1; $i < count($matches); $i++) {
                $tokens[] = trim($matches[$i]);
            }      
            
        } else {
            
            $tokens[] = trim($src);
        }
        
        return $tokens;
    }
    
    /**
     * Returns an array containing the last name, first name, middle name, and entity name
     * contained by the given provider name tokens
     * @param string[] $providerNameTokens the provider name tokens
     * @return string[] an associative array with the following keys (with matching values):
     * 'lastName', 'firstName', 'middleName', and 'entityName'
     */
    private function parseNames($providerNameTokens)
    {
        
        $personNames = [
            'lastName' => '',
            'firstName' => '',
            'middleName' => ''
        ];
        
        $entityName = '';
        
        $token1 = trim($providerNameTokens[0]);
        $token2 = trim($providerNameTokens[1]);
        
        if ($token1) {
            
            
            if ($this->isPerson($token1)) {
                //First provider name token is a person with format 'Last Name, First Name Middle Name'
                $personNames = $this->parsePersonNames($token1);
                $entityName = $token2;
            
            } else {
                //First provider name token is the name of an entity
                $entityName = $token1;
            }
        }
        
        
        if ($token2) {
            
            $ownerName = $this->parseOwnerName($token2);
            
            if ($ownerName) {
                //Second provider name token is the name of an owner with assumed format 'First Name Middle Name Last Name - Owner'
                $personNames = $this->parseOwnerNameAsPersonNames($ownerName);
                $entityName = $token1;
                
            } else {
                //Second provider name token is not the name of an owner but the name of an entity
                $entityName = $token2;
            }
        }
        
        return array_merge(['entityName' => $entityName], $personNames);
    }
    
    /**
     * Returns any 'dba' data from the given provider name tokens
     * @param string[] $providerNameTokens the provider name tokens
     * @return string[] array of 'dba' strings extracted from the given
     * provider name tokens, or an empty array if no 'dba' strings are found
     */
    private function parseDBA($providerNameTokens)
    {
        $dba = [];
        
        foreach ($providerNameTokens as $providerNameToken) {
            
            preg_match($this->dbaRegEx, $providerNameToken, $matches);
            
            if (! empty($matches)) {
                $dba[] = $matches[1];    
            }
        }
        
        return $dba;
    }
    
    /**
     * Trims the 'dba' strings from the provider name tokens
     * @param string[] $dba the 'dba' strings to remove
     * @param string[] $providerNameTokens the provider name tokens to trim
     * @return string[] the provider name tokens without 'dba' strings
     */
    private function trimDBA($dba, $providerNameTokens)
    {
        $results = [];
        
        foreach ($providerNameTokens as $providerNameToken) {
            $results[] = preg_replace($this->dbaRegEx, '', $providerNameToken);
        }
        
        return $results;
    }
    
    /**
     * Parses a CSV string containing NPI values and returns them as an array
     * @param string $src the CSV string containing NPI values
     * @return string[] an array of NPI strings extracted from the CSV string
     */
    private function parseNPI($src)
    {
        return trim($src) ? array_map('trim', str_getcsv($src)) : null;
    }
    
    /**
     * Returns the exclusion end date string from the given source string. The 
     * source string format is assumed to be <exclusion start date> '-' <exclusion end date>
     * @param string $src the source string containing the exclusion period
     * @return string the exclusion end date string
     */
    private function parseExclusionToDate($src)
    {
        $src = trim($src);
        
        if (! $src) {
            return '';
        }
        
        $tokens = explode("-", $src);
        
        return (empty($tokens) || count($tokens) !== 2) ? '' : $tokens[1];
    }
    
    /**
     * Returns the full description of the sanction type key, if it is defined
     * in this class' sanctionTypes array, or the sanction type key if it cannot 
     * be found
     * @param unknown $sanctionTypeKey the sanction type key
     * @return string the description text of the sanction type
     */
    private function parseSanctionType($sanctionTypeKey)
    {
        $sanctionTypeKey = trim($sanctionTypeKey);
        return isset($this->sanctionTypes[$sanctionTypeKey]) ? $this->sanctionTypes[$sanctionTypeKey] : $sanctionTypeKey;
    }
    
    /**
     * Returns true if the given source string indicates a person's name, otherwise 
     * returns false. Names of persons are assumed to follow <Last Name>, <First Name>
     * format.
     * @param string $src the string to check
     * @return boolean
     */
    private function isPerson($src)
    {
        return strpos($src, ',') !== false && ! preg_match($this->companySuffixRegex, $src);
    }
    
    /**
     * Parses the last, first and middle name strings from the given source string.
     * The format of the source string is assumed to be <Last Name>, <First Name> <Middle Name>...
     * @param string $src
     * @return string[] an associative array with the following keys (with matching values):
     * 'lastName', 'firstName', 'middleName'
     */
    private function parsePersonNames($src)
    {
        $personNames = [
            'lastName' => '',
            'firstName' => '',
            'middleName' => ''
        ];
        
        $nameTokens = str_getcsv($src);
        
        if (! empty($nameTokens)) {
        
            $personNames['lastName'] = trim($nameTokens[0]);
            
            if (count($nameTokens) > 1) {
                
                $firstAndMiddleNameTokens = explode(' ', trim($nameTokens[1]));
                
                $personNames['firstName'] = $firstAndMiddleNameTokens[0];
                
                if (count($firstAndMiddleNameTokens) > 1) {
                
                    $middleNameTokens = array_splice($firstAndMiddleNameTokens, 1);
                    $personNames['middleName'] = implode(' ', $middleNameTokens);
                }                
            }
        }
        
        return $personNames;
    }
    
    /**
     * Returns the string before '- Owner', if the source string contains ' - Owner',
     * otherwise returns blank 
     * @param string $src the source string
     * @return string
     */
    private function parseOwnerName($src)
    {
        $ownerName = '';
        
        preg_match($this->ownerRegex, $src, $matches);
        
        if (! empty($matches)) {
            $ownerName = $matches[1];
        }
        
        return $ownerName;
    }
    
    /**
     * Parses the last, first and middle names from the given owner name, which 
     * is assumed to be in <First Name> <Middle Name> <Last Name> format
     * @param string $ownerName the owner name string
     * @return array[] an associative array containing the following keys:
     * 
     */
    private function parseOwnerNameAsPersonNames($ownerName)
    {
        $ownerNameTokens  = explode(' ', trim($ownerName));
        
        $personName = '';
        
        if (! empty($ownerNameTokens)) {
            //Last token assumed to be the last name
            $lastName = array_pop($ownerNameTokens);
            //Any tokens following the last token are assumed to be the first and middle names
            $firstAndMiddleNames = ! empty($ownerNameTokens) ? implode(' ', $ownerNameTokens) : '';
            
            $personName = $lastName . ($firstAndMiddleNames ? (', ' . $firstAndMiddleNames) : '');
        }
       
        return $this->parsePersonNames($personName);
    }
}
