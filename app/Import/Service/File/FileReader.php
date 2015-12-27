<?php namespace App\Import\Service\File;


interface FileReader
{
    public function readRecords($fileName);
}