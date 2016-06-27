<?php

namespace App\Import\CredentialLists;

abstract class CredentialFileMaker
{
    public abstract function buildFile($destinationFilePath);

    public abstract function getFileType();
}
