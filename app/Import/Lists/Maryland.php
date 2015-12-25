<?php namespace App\Import\Lists;


use Symfony\Component\DomCrawler\Crawler;

class Maryland extends ExclusionList
{

    public $dbPrefix = 'md1';

    public $uri;

    public $retrieveOptions = array(
        'headerRow' => 0,
        'offset' => 1
    );

    public $fieldNames = array(
        'last_name',
        'first_name',
        'specialty',
        'sanction_type',
        'sanction_date',
        'address',
        'city_state_zip',
    );


    public function __construct()
    {
        $this->getUri();
    }


    protected function getUri()
    {
        $crawler = new Crawler(file_get_contents('https://mmcp.dhmh.maryland.gov/SitePages/Provider%20Information.aspx'));

        $link = $crawler->filter('#Column560 > div > h1 > div > ul:nth-child(11) > li:nth-child(4) > a')->attr('href');

        $this->uri = $link;
    }
}