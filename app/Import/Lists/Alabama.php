<?php namespace App\Import\Lists;

class Alabama extends ExclusionList
{
    public $dbPrefix = 'al1';
    
    public $uri = "http://medicaid.alabama.gov/documents/7.0_Fraud_Abuse/7.7_Suspended_Providers/7.7_Provider_Sanction_List_4-5-16.xls";
    
    public $type = 'xls';
    
    public $hashColumns = [
        'name_of_provider',
        'suspension_effective_date',
        'suspension_initiated_by'        
    ];
    
    public $dateColumns = [
        'suspension_effective_date' => 1
    ];
    
    public $fieldNames = [
        'name_of_provider',
        'suspension_effective_date',
        'suspension_initiated_by',
        'title',
        'aka_name',
        'provider_type'
    ];
    
    public $shouldHashListName = true;
    
    /**
     * The number of lines from the top of the exclusion list file preceding the 
     * start of the exclusion list data
     */
    private $headerOffset = 33;
    
    /**
     * A substring of the text at the bottom of the exclusion list file signifying
     * the end of the exclusion list data
     * @var unknown
     */
    private $footerIndicatorText = 'Any nurse aide listed as sanctioned'; 
    
    /**
     * Known titles of persons in the exclusion list (add new titles as needed)
     */
    protected $titles = [
        'Accountant/Operator'                                 => 'Accountant/Operator', 
        'Accounts Payable Clerk'                              => 'Accounts Payable Clerk', 
        'Administrative Assistant'                            => 'Administrative Assistant', 
        'Ambulance Company Owner'                             => 'Ambulance Company Owner', 
        'Attorney'                                            => 'Attorney', 
        'Audiologist'                                         => 'Audiologist', 
        'Bookkeeper'                                          => 'Bookkeeper', 
        'Business Manager'                                    => 'Business Manager', 
        'CRNP'                                                => 'CRNP', 
        'Caregiver'                                           => 'Caregiver', 
        'Certified Nursing Assistant'                         => 'Certified Nursing Assistant', 
        'Certified Nursing Asstistant'                        => 'Certified Nursing Asstistant',
        'Chairman/CEO'                                        => 'Chairman/CEO', 
        'Chiropractor'                                        => 'Chiropractor', 
        'Clinic Employee'                                     => 'Clinic Employee', 
        'Clinic Owner'                                        => 'Clinic Owner', 
        'Consulting Service Owner'                            => 'Consulting Service Owner', 
        'Contractor'                                          => 'Contractor', 
        'Counselor'                                           => 'Counselor', 
        'DMD'                                                 => 'DMD', 
        'DME Company Owner'                                   => 'DME Company Owner', 
        'DME Owner'                                           => 'DME Owner', 
        'DME Sales Representative'                            => 'DME Sales Representative', 
        'DME Supplier'                                        => 'DME Supplier', 
        'DME Supply Owner'                                    => 'DME Supply Owner', 
        'DME/Sales'                                           => 'DME/Sales', 
        'DO'                                                  => 'DO', 
        'Dental Technician'                                   => 'Dental Technician', 
        'Dentist'                                             => 'Dentist', 
        'Dentistry Employee'                                  => 'Dentistry Employee', 
        'Direct Care Employee'                                => 'Direct Care Employee', 
        'Direct Care Staff'                                   => 'Direct Care Staff', 
        'Drug Distributor'                                    => 'Drug Distributor', 
        'Employee of a Health Care Provider'                  => 'Employee of a Health Care Provider', 
        'Former County Judge'                                 => 'Former County Judge', 
        'Health Care Aide'                                    => 'Health Care Aide', 
        'Health Care Worker'                                  => 'Health Care Worker', 
        'Home Health Agency Employee'                         => 'Home Health Agency Employee', 
        'Hospital Staff Member'                               => 'Hospital Staff Member', 
        'IHSS Provider'                                       => 'IHSS Provider', 
        'LPN'                                                 => 'LPN', 
        'LPN & RN'                                            => 'LPN & RN', 
        'LPN & Vocational Nurse'                              => 'LPN & Vocational Nurse', 
        'LVN'                                                 => 'LVN', 
        'Lab Sales Representative'                            => 'Lab Sales Representative', 
        'Licensed Social Worker'                              => 'Licensed Social Worker', 
        'MD'                                                  => 'MD', 
        'Massage Therapist'                                   => 'Massage Therapist', 
        'Medicaid Assister'                                   => 'Medicaid Assister', 
        'Medical Group Employee'                              => 'Medical Group Employee', 
        'Mental Health Worker'                                => 'Mental Health Worker', 
        'Nurse'                                               => 'Nurse', 
        'Nurse Aide'                                          => 'Nurse Aide',
        'Nursing Assistant'                                   => 'Nursing Assistant',
        'Nursing Home Administrator'                          => 'Nursing Home Administrator', 
        'Nursing Home Employee'                               => 'Nursing Home Employee', 
        'Nursing Home Secretary/Receptionist'                 => 'Nursing Home Secretary/Receptionist', 
        'Occupational Therapist'                              => 'Occupational Therapist', 
        'Office Manager'                                      => 'Office Manager', 
        'Ombudsman Representative'                            => 'Ombudsman Representative', 
        'Optician'                                            => 'Optician', 
        'Owner of Hospice Facility'                           => 'Owner of Hospice Facility',
        'Paramedic'                                           => 'Paramedic', 
        'Pharmacist'                                          => 'Pharmacist', 
        'Pharmacy Employee'                                   => 'Pharmacy Employee', 
        'Pharmacy Owner'                                      => 'Pharmacy Owner', 
        'Pharmacy Technician'                                 => 'Pharmacy Technician', 
        'Podiatrist'                                          => 'Podiatrist', 
        'President of Medical Clinic'                         => 'President of Medical Clinic', 
        'Private Business Owner'                              => 'Private Business Owner', 
        'private citizen'                                     => 'private citizen',
        'Psychologist'                                        => 'Psychologist', 
        'RN'                                                  => 'RN', 
        'RN, Clinical Nurse Specialist & Public Health Nurse' => 'RN, Clinical Nurse Specialist & Public Health Nurse',
        'RN & ARNP'                                           => 'RN & ARNP', 
        'RN & CRNA'                                           => 'RN & CRNA', 
        'RN & LPN'                                            => 'RN & LPN', 
        'RN/LPN'                                              => 'RN/LPN', 
        'Rehabilitation Facility Owner'                       => 'Rehabilitation Facility Owner', 
        'Respiratory Therapist'                               => 'Respiratory Therapist', 
        'Sales/Marketing Agent'                               => 'Sales/Marketing Agent', 
        'Sitter'                                              => 'Sitter', 
        'Social Worker'                                       => 'Social Worker', 
        'Social Worker Aide'                                  => 'Social Worker Aide', 
        'Treasurer of Medical Clinic'                         => 'Treasurer of Medical Clinic', 
        'Vocational Nurse'                                    => 'Vocational Nurse'
    ];
    
    protected $nameSuffixes = [
        'Jr.'  => 'Jr.', 
        'Jr'   => 'Jr',  
        'Sr.'  => 'Sr.', 
        'Sr'   => 'Sr',  
        'I'    => 'I',   
        'II'   => 'II',  
        'III'  => 'III', 
        'IV'   => 'IV',  
        'V'    => 'V',   
        'VI'   => 'VI',  
        'VII'  => 'VII', 
        'VIII' => 'VIII',
        'IX'   => 'IX',  
        'X'    => 'X'    
    ];
    
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    public function parse() {
        if (empty($this->data)) {
            return;
        }
        
        $data = [];
        
        $rows = $this->trimHeaderRows();
        
        $providerType = '';
        
        foreach ($rows as $row) {
        
            //Ignore blank lines
            if (! trim(implode($row))) {
                continue;
            }
        
            //If we reached the footer of the file, then stop processing
            if ($this->isFooter($row)) {
                break;
            }
        
            if ($this->isProviderType($row)) {
                
                $providerType = $row[0];
                
            } else {
                
                $name = $row[0];
                
                $alias = $this->findAlias($name);
                
                //Remove alias from the name
                $name = $this->trimAlias($name);
                
                $title = '';
                
                //Do not extract any titles for provider names indicating institutions
                if (! $this->isInstitutionWithOwner($name)) {
                    
                    $title = $this->findTitle($name);

                    //Remove title from name
                    if ($title) {
                        $name = $this->trimTitle($name, $title);
                    }
                }
                
                //Name should now be clean of aka and title, set it as the value
                //of the provider_name column
                $row[0] = $name;
                
                //Add title, aka_name, and provider_type columns
                $row[] = $title;
                $row[] = $alias;
                $row[] = $providerType;
                
                $data[] = $row;
            }
        }
        
        $this->data = $data;        
    }
    
    public function setHeaderOffset($headerOffset)
    {
        $this->headerOffset = $headerOffset;
    }
    
    public function setFooterIndicatorText($footerIndicatorText)
    {
        $this->footerIndicatorText = $footerIndicatorText;
    }
    
    /**
     * Removes the header lines from this class' data property, returning only
     * the portion that contains the exclusion list data
     */
    private function trimHeaderRows() 
    {
        return array_slice($this->data, $this->headerOffset);
    }
    
    /**
     * Returns true if the given row contains provider type information,
     * false otherwise. A provider type row is assumed to have non-empty text
     * as its first element, while succeeding elements are blank.
     * @param array $row 
     * @return boolean
     */
    private function isProviderType($row)
    {
        return ! empty(head($row)) && empty(next($row)) && empty(next($row));
    }
    
    /**
     * Returns true if the given row is footer data, false otherwise.
     * @param array $row
     * @return boolean
     */
    private function isFooter($row)
    {
        return strpos(implode($row), $this->footerIndicatorText) !== false;
    }    
    
    /**
     * Finds the alias information from the given name
     * @param string $name
     * @return string
     */
    private function findAlias($name) 
    {
        if (preg_match('/\(aka ([^\)]+)| a\.k\.a\. (.+)/', $name, $matches)) {

            if ($matches[1]) return trim($matches[1]); //(aka ... variant)
            if ($matches[2]) return trim($matches[2]); //a.k.a. variant
            
        }
        
        return '';
    }
    
    /**
     * Removes the alias (i.e (aka ...) and a.k.a ...) subtrings from the given
     * string
     * @param string $name
     * @return string without the (aka ...) and a.k.a. substrings
     */
    private function trimAlias($name)
    {
        return trim(preg_replace('/\(aka (.+)| a\.k\.a\. (.+)/', '', $name));
    }
    
    /**
     * Returns true if the given name contains an ', Owner' string, indicating
     * that the name indicates an institution whose owner is specified
     * @param string $name the provider name
     * @return boolean
     */
    private function isInstitutionWithOwner($name)
    {
        if (preg_match('/,(\s*[O,o]wner\s*[\),]*.*)/', $name, $matches)) {
            
            if ($matches[1] && isset($this->titles[trim($matches[1])])) {
                //i.e. ('last name, first name, Owner of Hospice Facility') scenario should not be considered an institution with owner
                return false;
            } else {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Finds the title from within the given name. Assumes that the name is in
     * the format <last name>, <first name>, <title> or <last name>, <first name>
     * (title). Valid titles are defined in this class' titles field.
     * @param string $name the provider name
     * @return string the title from the name, or blank if a title cannot be found
     */
    private function findTitle($name)
    {
        
        $nameTokens = str_getcsv($name);
        $nameTokenCount = count($nameTokens);
        
        //Last token is assumed to contain the title string that we need to extract (this is applicable 99% of the time, see special case below)        
        $titleToken = trim($nameTokens[$nameTokenCount - 1]);
        
        //Some titles are specified within the name in parentheses like (Ambulance Operator), (DME Owner)
        //If that is the case, then extract the value within the parentheses and 
        //use that as the title token
        if (preg_match('/\(([^\)]*)\)$/', $titleToken, $matches)) {
            $titleToken = $matches[1];
        }
        
        $titleKey = isset($this->titles[$titleToken]) ? $this->titles[$titleToken] : '';
        
        if (empty($titleKey) && $nameTokenCount > 2) {

            //Special case : The last token may not have contained the full title string that's why there was no
            //titleKey returned - get everything  after the last name and first name tokens and see if that comes up with a match
            //in the valid titles (example of this is 'RN, Clinical Nurse Specialist & Public Health Nurse') 
            $titleTokens = [];

            //Gets all text after the last and first name
            for ($i = 2; $i < $nameTokenCount; $i++) {
                
                $nameToken = trim($nameTokens[$i]);
               
                //Include only tokens that are not name suffixes (i.e. not 'Jr.','I', 'II', 'III', etc.) as part of the title
                //to search
                if (! isset($this->nameSuffixes[$nameToken])) {
                    $titleTokens[] = $nameToken;
                }
                
            }
            
            $titleToken = implode(', ', $titleTokens);
            
            $titleKey = $titleToken && isset($this->titles[$titleToken]) ? $this->titles[$titleToken] : '';
        }
        
        return (! empty($titleKey) ? $this->titles[$titleKey] : '');
    }
    
    /**
     * Removes the specified title from the given name. Assumes that the name is in
     * the format <last name>, <first name>, <title> or <last name>, <first name>
     * (title)
     * @param string $name the provider name
     * @param string $title
     * @return string the name without the title
     */
    private function trimTitle($name, $title)
    {
        $title = preg_quote($title, '/');

        $regularTitleRegEx = ',?\s*'.$title.'$'; //looks for matches of the form , <title>
        $parenthesizedTitleRegEx = ',?\s*\(\s*'.$title.'\s*\)$'; //looks for matches of the form ", (<title>)" 
        
        return preg_replace(('/'.$regularTitleRegEx.'|'.$parenthesizedTitleRegEx.'/'), '', trim($name));
    }
}
