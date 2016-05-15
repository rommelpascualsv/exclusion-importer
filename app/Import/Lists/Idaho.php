<?php namespace App\Import\Lists;

class Idaho extends ExclusionList
{

    public $dbPrefix = 'id1';

    public $uri = 'http://healthandwelfare.idaho.gov/Portals/0/Providers/Medicaid/ProviderExclusionList.pdf';

    public $pdfToText = "java -Dfile.encoding=utf-8 -jar ../etc/tabula.jar -p all -u -g -r";

    public $type = 'pdf';

    public $hashColumns = [
        'name',
        'exclusion_start_date',
        'date_eligible_for_reinstatement',
        'date_reinstated'
    ];

    public $dateColumns = [
        'exclusion_start_date' => 1,
        'date_eligible_for_reinstatement' => 2,
        'date_reinstated' => 3
    ];

    public $fieldNames = [
        'name',
        'exclusion_start_date',
        'date_eligible_for_reinstatement',
        'date_reinstated',
        'additional_information'
    ];

    public $shouldHashListName = true;
    
    protected $tableHeaders = [
        '"",Exclusion,Date Eligible,Date,',
        'Name,Start,for,Reinstated,Additional Information',
        '"",Date,Reinstatement,,'
    ];

    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }

    public function parse()
    {
        
        $rows = preg_split('/(\r)?\n(\s+)?/', $this->data);
        
        $data = [];
        
        for ($i = 0; $i < count($rows); $i++) {
            
            //Remove any line breaks within the parsed row data text
            $row = preg_replace('/\r\n?/', '', $rows[$i]);
            
            if (empty($row) || $this->isHeader($row)) {
                continue;
            }
            
            $columns = str_getcsv($row);
            
            if ($this->isContinuationOfPreviousRow($columns)) {
                //This happens when a supposedly single entry in the table gets broken
                //to two rows in different pages because of page breaks. In this case,
                //we should join the current column info  with the previous row's
                //column info in this case       
                $previousRow = $this->findPreviousDataRow($rows, $i);
                
                $columns = $this->mergeRowColumns($previousRow, $row);
                
                //Remove the last pushed columns from data, since it contains incomplete
                //data and should be be replaced with the merged column data of 
                //the previous and current row
                array_pop($data);
            }
            
            $data[] = $columns;
        }
        
        $this->data = $data;
    }
    
    protected function valueForUnparsableDate($columnName, $columnValue, $row)
    {
        //Some values are not dates, like 'Indefinite', just return whatever 
        //is specified in the read data as the value
        return trim($columnValue);
    }
    
    /**
     * Returns true if the given row corresponds to a table header of the
     * exclusion list, false otherwise. 
     * @param string $row
     * @return boolean
     */
    private function isHeader($row)
    {
        return array_search(trim(str_replace("\r", "", $row)), $this->tableHeaders) !== false;
    }
    
    /**
     * Returns true if the given column data corresponds to a row that is just
     * a continuation of the previous row columns and should not be treated as
     * another entry in the exclusion list
     * @param array $columns the column data of the current row
     * @return boolean
     */
    private function isContinuationOfPreviousRow($columns)
    {
        return empty($columns[$this->dateColumns['exclusion_start_date']]);
    }
    
    /**
     * Appends the column values contained by 'current' to the values of 'previous'
     * and returns the merged values as an array. This method assumes that
     * previous and current have the same number of columns (represented as CSV) 
     * @param string $previousRow CSV string of the previous row's data
     * @param string $currentRow CSV string of the current row's data
     * @return array
     */
    private function mergeRowColumns($previousRow, $currentRow)
    {
        //If there is no previous row, then we have nothing to merge the current
        //row to - just return the current row
        if (! $previousRow) return str_getcsv($currentRow);
        
        $previousColumns = str_getcsv($previousRow);
        $currentColumns = str_getcsv($currentRow);
        $mergedColumns = [];
        
        for ($i = 0; $i < count($previousColumns); $i++) {
            $mergedColumns[] = trim($previousColumns[$i].' '.$currentColumns[$i]);
        }
        
        return $mergedColumns;
    }
    
    /**
     * Finds and returns the first non-header row in the given rows array, 
     * starting from fromIndex to the start of the array
     * @param array $rows the rows of data, which includes headers 
     * @param int $fromIndex the index from which to start looking for the 
     * previous data row
     * @return string|NULL
     */
    private function findPreviousDataRow($rows, $fromIndex)
    {
        for ($i = ($fromIndex - 1); $i >= 0; $i--) {
            
            $row = trim($rows[$i]);

            //Return the first row encountered that is not a table header
            if ($row && array_search($row, $this->tableHeaders) === false) {
                return $row;
            }
        }
        
        return null;
    }
}
