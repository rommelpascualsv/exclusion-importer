<?php namespace App\Import\Lists;


use Symfony\Component\DomCrawler\Crawler;

class Georgia extends ExclusionList
{

    public $dbPrefix = 'ga1';


    public $uri;


    public $type = 'xlsx';


    public $fieldNames = [
        'last_name',
        'first_name',
        'business_name',
        'general',
        'state',
        'sanction_date'
    ];


    public $hashColumns = [
        'last_name',
        'first_name',
        'business_name',
        'sanction_date'
    ];


    public $retrieveOptions = [
        'headerRow' => 2,
        'offset' => 3
    ];


    public $dateColumns = [
        'sanction_date' => 5
    ];


    public function __construct()
    {
        parent::__construct();
        $this->uri = $this->getUri();
    }


    protected function getUri()
    {
        $crawler = new Crawler(file_get_contents('http://dch.georgia.gov/georgia-oig-exclusions-list'));
        $link = $crawler->filter('#block-system-main > div > div > article > div:nth-child(2) > div > div > div > p:nth-child(6) > a')->attr('href');

        return 'https://dch.georgia.gov/' . $link;
    }

}
