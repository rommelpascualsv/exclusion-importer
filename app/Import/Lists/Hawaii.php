<?php namespace App\Import\Lists;

use Symfony\Component\DomCrawler\Crawler;

class Hawaii extends ExclusionList
{
    public $dbPrefix = 'hi1';

    public $uri;

    public $type = 'xls';

    public $retrieveOptions = [
        'headerRow' => 24,
        'offset' => 25
    ];

    public $fieldNames = [
        'last_name_or_business',
        'first_name',
        'middle_initial',
        'medicaid_provide_id_number',
        'last_known_program_or_provider_type',
        'exclusion_date',
        'reinstatement_date'
    ];

    public $dateColumns = [
      'exclusion_date' => 5
    ];

    public function __construct()
    {
        parent::__construct();
        $this->uri = $this->getUri();
    }

    protected function getUri()
    {
        $crawler = new Crawler(file_get_contents('http://www.med-quest.us/providers/ProviderExclusion_ReinstatementList.html'));
        $link = $crawler->filter('tr th div ul.bodytext li:nth-child(2) a')->attr('href');

        return str_replace(' ', '%20', str_replace('..', 'http://www.med-quest.us', $link));
    }
    
    public function preProcess()
    {
        $this->parse();
        parent::preProcess();
    }
    
    public function parse()
    {
        $exclusionDateIndex = array_search("exclusion_date", $this->fieldNames);
        
        $this->data = array_map(function($row) use ($exclusionDateIndex) {
            
            // set blank if date is "-"
            if ($row[$exclusionDateIndex] === "-") {
                $row[$exclusionDateIndex] = "";
            }
            
            return $row;
        }, $this->data);
    }
}
