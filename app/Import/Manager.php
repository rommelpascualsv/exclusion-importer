<?php

namespace App\Import;


class Manager
{

    public $configs;

    public function __construct($listPrefix)
    {
        $this->configs = config("import.$listPrefix");
    }

    public function getList()
    {
        if ($this->configs['class'] == NULL)
            throw new \RuntimeException("Unsupported Exclusion List prefix");

        $class = "App\\Import\\Lists\\{$this->configs['class']}";

        return new $class;
    }
}