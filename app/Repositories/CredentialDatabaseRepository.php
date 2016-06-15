<?php

namespace App\Repositories;

class CredentialDatabaseRepository implements Repository
{

    public function create($record)
    {
        // TODO: Implement create() method.
    }

    public function clear()
    {
        // TODO: Implement clear() method.
    }

    public function find($prefix)
    {
        $results = app('db')
                   ->table('credential_databases')
                   ->where('prefix', $prefix)
                   ->get();

        return head($results);
    }
}
