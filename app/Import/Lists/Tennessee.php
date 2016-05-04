<?php namespace App\Import\Lists;

class Tennessee extends ExclusionList
{
    public $dbPrefix = 'tn1';
    
    //Note that the columns need to be adjusted if there are any added/deleted
    //columns or changes to the widths of the columns in the PDF
    public $pdfToText = "java -Dfile.encoding=utf-8 -jar ../etc/tabula.jar -p all --columns 216,273,330,384,684";

    public $uri = 'http://www.tn.gov/assets/entities/tenncare/attachments/terminatedproviderlist.pdf';

    public $type = 'pdf';

    public $fieldNames = [
        'last_name',
        'first_name',
        'middle_name',
        'npi',
        'begin_date',
        'reason',
        'end_date'
    ];

    public $hashColumns = [
        'last_name',
        'first_name',
        'middle_name',
        'npi',
        'begin_date',
        'end_date'
    ];

    public $dateColumns = [
        'begin_date' => 4,
        'end_date'   => 6
    ];

    public $shouldHashListName = true;
    
    public $npiColumnName = 'npi';
    
    protected $headerLine = 'Last Name,First Name,NPI,Begin Date,Reason,End Date';
    
    /**
     * Array of entries in the exclusion list PDF whose 'Last Name' column values 
     * overflow to the 'First Name' column
     * @var array
     */
    protected $overflowingNames = [
        'The Rainbow Center of Children & Adolescent'
    ];

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
            
            $row = preg_replace('/\/r\/n?/', '', $row);
            
            $columns = str_getcsv($row);
            
            $lastName = trim($columns[0]);
            $firstName = trim($columns[1]);
            $npi = trim($columns[2]);
            $beginDate = trim($columns[3]);
            $reason = trim($columns[4]);
            $endDate = trim($columns[5]);
            $middleName = '';
            
            if ($this->isOverflowingName($lastName, $firstName)) {
                //Some entries in the list have Last Name values that overflow
                //to the First Name column. tabula 'chops' these values and puts
                //them in the last and first name columns. We need to correct these entries
                //by concatenating the last and first name columns.
                $lastName = trim($lastName.$firstName); //Last name becomes the concatenated value of the first and second columns
                $firstName = ''; //First Name set to blank
            }
            
            if ($firstName) {
                
                $middleName = $this->findMiddleName($firstName);
                
                if ($middleName) {
                    $firstName = $this->trimMiddleName($firstName, $middleName);
                }
                
            }
            
            $npi = [$npi]; //Downstream processing expects NPI to be an array, so we wrap it in an array
            
            $data[] = [
                $lastName,
                $firstName,
                $middleName,
                $npi,
                $beginDate,
                $reason,
                $endDate
            ];
        }

        $this->data = $data;
    }
    
    private function isHeader($row)
    {
        return $this->headerLine === $row;
    }
    
    /**
     * Returns true if the given last name and first name, when combined correspond
     * to an entry in the original PDF whose last name column value overflows to the 
     * first name column
     * @param string $lastName
     * @param string $firstName
     */
    private function isOverflowingName($lastName, $firstName)
    {
        return ! empty($lastName) && ! empty($firstName) && in_array($lastName.$firstName, $this->overflowingNames);
    }
    
    /**
     * Returns the middle name portion of the given first name column value.
     * @param string $firstName
     * @return string
     */
    private function findMiddleName($firstName)
    {
        $firstNameTokens = explode(' ', $firstName);
        $firstNameTokenCount = count($firstNameTokens);
        
        //If we only have 0 or 1 tokens in the first name, then there is no middle name.
        //Otherwise the middle name is everything after the first token in the first name
        return $firstNameTokenCount < 2 ? '' : trim(implode(' ', array_splice($firstNameTokens, 1)));
        
    }
    
    /**
     * Removes the middle name out of the first name and returns the trimmed
     * version of the first name
     * @param string $firstName
     * @param unknown $middleName
     */
    private function trimMiddleName($firstName, $middleName)
    {
        return str_replace($middleName, '', $firstName);
    }
}
