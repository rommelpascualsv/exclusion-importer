<?php namespace App\Import\Service\Exclusions;

use GuzzleHttp\Client;
use App\Import\Lists\ExclusionList;
use App\Import\Service\DataCsvConverter;
use Symfony\Component\DomCrawler\Crawler;

class HTMLRetriever extends Retriever
{
    /**
     * @var \App\Import\Service\DataCsvConverter
     */
    protected $dataConverter;

    /**
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    protected $domCrawler;

    /**
     * @param Crawler $domCrawler
     * @param DataCsvConverter $dataConverter
     */
    public function __construct(Crawler $domCrawler, DataCsvConverter $dataConverter)
    {
        $this->domCrawler = $domCrawler;
        $this->dataConverter = $dataConverter;
    }

    /**
     * @param ExclusionList $list
     *
     * @return ExclusionList
     */
    public function retrieveData(ExclusionList $list)
    {
        $data = [];

        // Implement multiple file upload use comma searated
        $uri = $this->splitURI($list->uri);

        foreach ($uri as $key => $value) {
            
            $htmlContent = $this->getContentFrom($value, $list);
            
            $this->domCrawler->addHtmlContent($htmlContent);

            $table = $this->domCrawler->filter($list->retrieveOptions['htmlFilterElement']);

            $columnsArray = $table->filter($list->retrieveOptions['rowElement'])->each(function (Crawler $node) use ($list) {
                return $node->filter($list->retrieveOptions['columnElement'])->each(function (Crawler $node) {
                    return $node->text();
                });
            });

            foreach ($columnsArray as $key => $value) {
                if (! array_filter($value)) {
                    unset($columnsArray[$key]);
                }
            }

            if ($list->retrieveOptions['headerRow'] == 1) {
                array_shift($columnsArray);
            }
            // Merge data
            $data = array_merge($data, $columnsArray);
        }

        return $data;
    }
    
    private function getContentFrom($uri, $list)
    {
        $content = '';
        
        if ($this->isRemoteURI($uri)) {
            
            $client = new Client([
                'base_uri' => $list->uri
            ]);
        
            $response = $client->request('GET', $list->urlSuffix, $list->requestOptions);
        
            $content = $response->getBody()->getContents();
        
        } else {
            $content = file_get_contents($uri);
        }
        
        return $content;
    }
}
