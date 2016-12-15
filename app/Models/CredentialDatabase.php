<?php

namespace App\Models;

class CredentialDatabase
{
    private $id;
    private $prefix;
    private $description;
    private $sourceUri;
    private $autoSeed;
    private $lastImportHash;
    private $lastImportDate;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return CredentialDatabase
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param mixed $prefix
     * @return CredentialDatabase
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return CredentialDatabase
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSourceUri()
    {
        return $this->sourceUri;
    }

    /**
     * @param mixed $sourceUri
     * @return CredentialDatabase
     */
    public function setSourceUri($sourceUri)
    {
        $this->sourceUri = $sourceUri;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAutoSeed()
    {
        return $this->autoSeed;
    }

    /**
     * @param mixed $autoSeed
     * @return CredentialDatabase
     */
    public function setAutoSeed($autoSeed)
    {
        $this->autoSeed = $autoSeed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastImportHash()
    {
        return $this->lastImportHash;
    }

    /**
     * @param mixed $lastImportHash
     * @return CredentialDatabase
     */
    public function setLastImportHash($lastImportHash)
    {
        $this->lastImportHash = $lastImportHash;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastImportDate()
    {
        return $this->lastImportDate;
    }

    /**
     * @param mixed $lastImportDate
     * @return CredentialDatabase
     */
    public function setLastImportDate($lastImportDate)
    {
        $this->lastImportDate = $lastImportDate;
        return $this;
    }

    public function save()
    {
        return app('db')->table('credential_databases')->where('id', $this->id)->update([
            'prefix' => $this->getPrefix(),
            'description' => $this->getDescription(),
            'source_uri' => $this->getSourceUri(),
            'auto_seed' => $this->getAutoSeed(),
            'last_import_hash' => $this->getLastImportHash(),
            'last_import_date' => $this->getLastImportDate()
        ]);
    }
}
