<?php

namespace App\Import\CredentialLists;

use GuzzleHttp\Client;
use League\Flysystem\Filesystem;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use Symfony\Component\DomCrawler\Crawler;

class NppesCredentialFileMaker extends CredentialFileMaker
{
    const WEEKLY_INCREMENTAL_NPI_FILES_LINK_SELECTOR ='NPPES Data Dissemination - Weekly Update';

    const CONNECT_TIMEOUT = 10;

    public function buildFile($destinationFile)
    {
        $sourceFile = $this->createSourceFile();

        try {

            $sourceFileLink = $this->getSourceFileLink();

            info('Downloading credential list file for Nppes from ' . $sourceFileLink . ' to ' . $sourceFile);

            $this->downloadTo($sourceFile, $sourceFileLink);

            $this->extractCredentialFileTo($destinationFile, $sourceFile);

            info('Successfully extracted credential list file to ' . $destinationFile);

        } catch (\Exception $e) {

            error('Unable to generate credential file for NPPES : ' . $e->getMessage());
            throw $e;

        } finally {

            if ($sourceFile && file_exists($sourceFile)) {
                unlink($sourceFile);
            }
        }

    }

    private function createSourceFile()
    {
        return tempnam(sys_get_temp_dir(), str_random(4));
    }

    private function getSourceFileLink()
    {
        $crawler = new Crawler(file_get_contents($this->sourceUri));

        $link = $this->getWeeklyUpdateLink($crawler);

        if (empty($link)) {
            throw new \RuntimeException('Unable to find link for weekly incremental NPI files of Nppes from ' . $this->sourceUri);
        }

        return $this->sourceUri . '/.' . $link;
    }

    private function getWeeklyUpdateLink($crawler)
    {
        $dateRanges = [
            '1_week_ago'  => date('mdy',strtotime('monday last week')) . '-' . date('mdy',strtotime('sunday last week')),
            '2_weeks_ago' => date('mdy',strtotime('monday last week -1 week')) . '-' . date('mdy',strtotime('sunday last week -1 week')),
            '3_weeks_ago' => date('mdy',strtotime('monday last week -2 weeks')) . '-' . date('mdy',strtotime('sunday last week -2 weeks')),
            '4_weeks_ago' => date('mdy',strtotime('monday last week -3 weeks')) . '-' . date('mdy',strtotime('sunday last week -3 weeks'))
        ];

        $link = null;

        //Find the most recent weekly update link and return its link address
        foreach($dateRanges as $dateRange)
        {
            $weeklyUpdateLink = $crawler->selectLink(self::WEEKLY_INCREMENTAL_NPI_FILES_LINK_SELECTOR . ' - ' .  $dateRange);

            if (! empty($weeklyUpdateLink)) {
                $link = $weeklyUpdateLink->attr('href');
                break;
            }
        }

        return $link;
    }

    private function downloadTo($sourceFile, $sourceFileLink)
    {
        $client = new Client();

        $client->get($sourceFileLink, [
            'sink' => $sourceFile,
            'connect_timeout' => self::CONNECT_TIMEOUT,
            'verify' => false
        ]);
    }

    private function extractCredentialFileTo($destinationFile, $sourceFile)
    {
        $zipFile = new Filesystem(new ZipArchiveAdapter($sourceFile));

        $credentialFilePathInArchive = '';

        foreach ($zipFile->listContents() as $file) {

            if ($file['extension'] === 'csv' && ! str_contains($file['filename'], 'FileHeader')) {
                $credentialFilePathInArchive = $file['path'];
                break;
            }

            $zipFile->delete($file['path']);
        };

        if (empty($credentialFilePathInArchive)) {
            throw new \RuntimeException('No credential file was found in zip archive');
        }

        file_put_contents($destinationFile, file_get_contents('zip://' . $sourceFile . '#' . $credentialFilePathInArchive));
    }

}