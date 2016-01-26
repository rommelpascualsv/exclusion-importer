<?php namespace App\Import\Lists;


use Symfony\Component\DomCrawler\Crawler;

class Hawaii extends ExclusionList
{

    public $dbPrefix = 'hi1';

    public $uri;

    public $type = 'xls';

    public $retrieveOptions = array(
        'headerRow' => 24,
        'offset' => 25
    );

    public $fieldNames = array(
        'last_name_or_business',
        'first_name',
        'middle_initial',
        'medicaid_provide_id_number',
        'last_known_program_or_provider_type',
        'exclusion_date',
        'reinstatement_date'
    );


    public function __construct()
    {
        $this->getUri();
    }


    protected function getUri()
    {
        $crawler = new Crawler(file_get_contents('http://www.med-quest.us/providers/ProviderExclusion_ReinstatementList.html'));

        $link = $crawler->filter('tr th div ul.bodytext li:nth-child(2) a')->attr('href');

        $this->uri = str_replace(' ', '%20', str_replace('..', 'http://www.med-quest.us', $link));
    }
}