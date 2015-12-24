<?php

namespace app\Import;


class Manager
{

    public $configs;

    public function __construct($listPrefix)
    {
        $this->configs = config("import.$listPrefix");
    }
}