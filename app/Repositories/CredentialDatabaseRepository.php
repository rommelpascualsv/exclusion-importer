<?php

namespace App\Repositories;

use App\Models\CredentialDatabase;

class CredentialDatabaseRepository implements Repository
{

    public function create($record)
    {
        // TODO: Implement create() method.
    }

    public function clear()
    {
        app('db')->table('credential_databases')->truncate();
    }

    public function find($prefix)
    {
        $results = app('db')
                   ->table('credential_databases')
                   ->where('prefix', $prefix)
                   ->get();

        $record = head($results);

        if (! $record) {
            return null;
        }

        $credentialDatabase = new CredentialDatabase();

        $credentialDatabase->setId($record->id)
            ->setPrefix($record->prefix)
            ->setDescription($record->description)
            ->setSourceUri($record->source_uri)
            ->setAutoSeed($record->auto_seed)
            ->setLastImportHash($record->last_import_hash)
            ->setLastImportDate($record->last_import_date);

        return $credentialDatabase;

    }
}
