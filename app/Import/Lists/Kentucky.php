<?php namespace App\Import\Lists;

use Symfony\Component\DomCrawler\Crawler;
use \App\Import\Lists\ProviderNumberHelper as PNHelper;

class Kentucky extends ExclusionList
{
    public $dbPrefix = 'ky1';

    public $uri;

    public $type = 'xls';

    public $retrieveOptions = [
        'headerRow' => 0,
        'offset' => 1
    ];

    public $fieldNames = [
        'first_name',
        'last_name_or_practice',
        'npi',
        'license',
        'effective_date',
        'reason_for_term',
        'timeframe_of_term'
    ];

    /**
     * @var array
     */
    public $hashColumns = [
        'first_name',
        'last_name_or_practice',
        'npi',
        'effective_date',
    ];

    /**
     * @var array
     */
    public $dateColumns = [
        'effective_date' => 4
    ];
    
    public $shouldHashListName = true;
    
    protected $npiColumnName = "npi";
    
    public function __construct()
    {
        parent::__construct();
        $this->uri = $this->getUri();
    }

    protected function getUri()
    {
        $crawler = new Crawler(file_get_contents('http://chfs.ky.gov/dms/term.htm'));

        $link = $crawler->filter('#section1ContentPlaceholderControl > ul:nth-child(5) > li > a:nth-child(3)')->attr('href');

        return 'http://chfs.ky.gov' . $link;
    }
    
    /**
     * @inherit preProcess
     */
    public function preProcess()
    {
    	$this->parse();
    	parent::preProcess();
    }
    
    /**
     * Parse the input data
     */
    private function parse()
    {
    	$data = [];
    	 
    	// iterate each row
    	foreach ($this->data as $row) {
    	    
    	    $npiColumnIndex = $this->getNpiColumnIndex();
    	    	
    	    // set npi number array
    	    $row = PNHelper::setNpiValue($row, PNHelper::getNpiValue($row, $npiColumnIndex), $npiColumnIndex);
    	    
    	    $data[] = $row;
    	}
    	 
    	// set back to global data
    	$this->data = $data;
    }
}
