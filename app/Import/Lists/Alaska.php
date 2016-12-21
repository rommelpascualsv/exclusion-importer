<?php namespace App\Import\Lists;

use Smalot\PdfParser\Parser;

class Alaska extends ExclusionList
{
    public $dbPrefix = 'ak1';

    public $pdfToText = "java -Dfile.encoding=utf-8 -jar ../etc/tabula.jar -p 2-9 -c 91,279,390,528,588,757";

    public $uri = "http://dhss.alaska.gov/Commissioner/Documents/PDF/AlaskaExcludedProviderList.pdf";
    
    public $type = 'pdf';

    public $fieldNames = [
        'exclusion_date',
        'last_name',
        'first_name',
        'middle_name',
        'provider_type',
        'exclusion_authority',
        'exclusion_reason'
    ];

    public $hashColumns = [
        'exclusion_date',
        'last_name',
        'first_name',
        'middle_name',
        'provider_type',
    ];

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $dateColumns = [
        'exclusion_date' => 0
    ];

    /**
     * A regex to match '<full month name> <four-digit year>
     * @var string
     */
    private $monthNameAndFourDigitYearRegex = '/\b(?:Jan(?:uary)?|Feb(?:ruary)?|Mar(?:ch)?|Apr(?:il)?|May?|Jun(?:e)?|Jul(?:y)?|Aug(?:ust)?|Sep(?:tember)?|Oct(?:ober)?|Nov(?:ember)?|Dec(?:ember)?) (?:19[7-9]\d|2\d{3})(?=\D|$)/';

    public function preProcess()
    {
    	$this->parse();
    	parent::preProcess();
    }

    public function retrieveData()
    {
        $this->pdfToText = "java -Dfile.encoding=utf-8 -jar ../etc/tabula.jar -p 2-" .$this->getPdfPageCount();
        parent::retrieveData();
    }


    public function parse()
    {
    	// remove all page numbers
        $this->data = preg_replace('/"",,Page," \\d of \\d",,/', "", $this->data);

    	$rows = preg_split('/(\r)?\n(\s+)?/', $this->data);

    	$data = [];
    	foreach ($rows as $key => $value) {
    		 
    		// do not include if row is empty
    		if (empty($value) || $this->isHeader($value)) {
    			continue;
    		}
    		
    		// convert string row to comma-delimited array
    		$columns = str_getcsv($value);
    		
    		// applies specific overrides
    		$columns = $this->applyOverrides($columns);
    		
    		// populate the array data
    		array_push($data, $columns);
    	}
    
    	$this->data = $data;
    }

    /**
     * Applies the specific overrides to correct the data
     * @param array $columns the column array
     * @return array $columns the column array
     */
    private function applyOverrides($columns)
    {
    	$columns = $this->populateFirstMiddleName($columns);
    	
    	return $columns;
    }
    
    /**
     * Populates the first and middle name columns
     * @param array $columns the column array
     * @return array $columns the column array
     */
    private function populateFirstMiddleName($columns)
    {
    	$firstMiddle = explode(" ", $columns[2], 2);
    	 
    	if (count($firstMiddle) == 1) {
    		$firstMiddle[1] = "";
    	}
    	 
    	array_splice($columns, 2, 1, $firstMiddle);
    	 
    	return $columns;
    }
    
    /**
     * Determines if the value is a header.
     * @param string $value the string row value
     * @return boolean returns true if value is header, otherwise false.
     */
    private function isHeader($value)
    {
        $value = str_replace(["\r", ',', '"'], '', $value);

        return strpos($value, 'Alaska Medical Assistance Excluded Provider List') !== false
            || strpos($value, 'EXCLUSION EXCLUSION') !== false
            || strpos($value, 'EXCLUSION REASON') !== false
            || strpos($value, 'DATEAUTHORITY') !== false
            || preg_match($this->monthNameAndFourDigitYearRegex, $value) === 1;
    }

    private function getPdfPageCount()
    {
        $parsePdf = new Parser();
        $pdf = $parsePdf->parseFile($this->uri);
        $pdfPageCount = count($pdf->getPages());
        return $pdfPageCount;
    }
}
