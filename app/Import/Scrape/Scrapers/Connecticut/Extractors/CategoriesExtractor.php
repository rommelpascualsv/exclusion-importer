<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Import\Scrape\Scrapers\Connecticut\Extractors;

use App\Import\Scrape\Crawlers\ConnecticutCrawler;
use Symfony\Component\DomCrawler\Crawler;
use App\Import\Scrape\Components\ScrapeFilesystemInterface;
use App\Import\Scrape\Scrapers\Connecticut\MainPage;
use Goutte\Client;

/**
 * Description of CategoriesExtractor
 *
 * @author Lenovo
 */
class CategoriesExtractor
{

    /**
     * @var ConnecticutCrawler
     */
    protected $crawler;

    /**
     * @var ScrapeFilesystemInterface
     */
    protected $filesystem;

    /**
     * Instantiate
     * @param ConnecticutCrawler $crawler
     */
    public function __construct(MainPage $page, ScrapeFilesystemInterface $filesystem)
    {
        $this->page = $page;
        $this->filesystem = $filesystem;
    }

    /**
     * Save extracted categories to a JSON
     */
    public function extractCategories()
    {
        $categoryHeadersCrawler = $this->page->getNodesByCss('.panel-heading', 'cannot find heading');

        $categories = $this->getCategoriesData($categoryHeadersCrawler);

        $this->filesystem->put(
                'extracted/connecticut-categories.json', json_encode($categories, JSON_PRETTY_PRINT
        ));
    }

    public function getMainCrawler()
    {
        return $this->crawler->getMainCrawler();
    }

    public function getCategoryHeadersCrawler(Crawler $mainCrawler)
    {
        return $mainCrawler->filter($this->crawler->getSelector('main', 'category_header'));
    }

    /**
     * Get category data
     * @param Crawler $categoryHeaderCrawler
     * @return array
     */
    public function getCategoryData(Crawler $categoryHeaderCrawler, $i = 0)
    {
        $name = $this->getCategoryName($categoryHeaderCrawler);
        $options = $this->getCategoryOptions($categoryHeaderCrawler, $i);

        return [
            'name' => $name,
            'options' => $options
        ];
    }

    public function getCategoriesData(Crawler $categoryHeadersCrawler)
    {
        $categories = [];

        $categoryHeadersCrawler->each(function(Crawler $categoryHeaderNode, $i) use (&$categories) {
            $data = $this->getCategoryData($categoryHeaderNode, $i);
            $key = $this->getKey($data['name']);

            $categories[$key] = $data;
        });

        return $categories;
    }

    /**
     * Get category text
     * @param Crawler $nodeCrawler
     * @return string
     */
    public function getCategoryName(Crawler $nodeCrawler)
    {
        $text = trim(
                str_replace(
                        [
            '-  (click this bar to expand/collapse group)',
            "\xA0",
            "\xC2"
                        ], '', $nodeCrawler->text()
                )
        );

        return $text;
    }
    
    public function getOptionsData($crawler)
    {
        return array_filter(array_map(function($a) {
                    $name = $this->getOptionName(strip_tags(trim($a)));
                    $file_name = $this->getKey($name);

                    return (!$file_name || !$this->getOptionName(strip_tags(trim($a))))
                    ? false : [
                        'name' => $this->getOptionName(strip_tags(trim($a))),
                        'file_name' => $file_name
                    ];
                }, explode('<br>', $crawler->html())
        ));
    }

    /**
     * Get category options
     * @param Crawler $nodeCrawler
     * @return array
     */
    public function getCategoryOptions(Crawler $nodeCrawler, $i = 0)
    {
        $options = [];

        $this->page->getNodesByCss('.panel-default .panel-body', 'Something went wrong!')->each(function(Crawler $crawler) use (&$options, $i) {
            $optionsData = $this->getOptionsData($crawler);
            $data = [];
            $counter = 0;
            
            // Get checkbox for options
            $this->page->getNodesByCss('span', 'Something went wrong!', $crawler)->each(function(Crawler $spanCrawler) use (&$optionsData, &$counter, &$data) {
                $data = $this->getFieldName($spanCrawler, $counter);
                $optionsData[$counter]['field_name'] = $data;
                $counter++;
            });

            array_filter($optionsData);
        });

        return $options;
    }

    /**
     * Get options data
     * @param Crawler $optionsNode
     * @return array
     */
    public function getFieldName(Crawler $optionsNode, $index = 0)
    {
        $checkboxNode = $this->page->getNodesByCss('input[type=checkbox]', 'Something went wrong', $optionsNode);
//        $checkboxNode = $optionsNode->filter('input[type=checkbox]');

        if ($checkboxNode->count() == 0) {
            throw new \Exception(sprintf('Option name checkbox cannot be found on node %d', $index));
        }

        return $checkboxNode->attr('name');
    }

    /**
     * Get key
     * @param string $name
     * @return string
     */
    protected function getKey($name)
    {
        return strtolower($this->underscorify($name));
    }

    /**
     * Get file name
     * @param string $name
     * @return string
     */
    public function getFileName($name)
    {
        return $this->underscorify($name);
    }

    /**
     * Underscorify text
     * @param string $text
     * @return string
     */
    public function underscorify($text)
    {
        return preg_replace(
                ['/[^a-zA-Z0-9 ]/', '/ /', '/_{2,}/', '/^_|_$/'], [' ', '_', '_', ''], $text
        );
    }

    /**
     * Get option name
     * @param string $text
     * @return string
     */
    protected function getOptionName($text)
    {
        return $this->stripNodeText('(No Fee Required)', $text);
    }

    /**
     * Strip node text
     * @param string $needle
     * @param string $haystack
     * @return string
     */
    protected function stripNodeText($needle, $haystack)
    {
        return trim(str_replace([$needle, "\xA0", "\xC2"], '', $haystack));
    }

    public static function create(Client $client, ScrapeFilesystemInterface $filesystem)
    {
        $mainPage = MainPage::scrape($client);

        return new static($mainPage, $filesystem);
    }

}
