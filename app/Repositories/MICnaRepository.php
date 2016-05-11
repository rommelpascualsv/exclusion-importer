<?php namespace App\Repositories;

use MongoDB;

class MICnaRepository implements Repository
{
    protected $collection;

    public function __construct(MongoDB $client)
    {
        $this->collection = $client->mi_cna;
        $this->collection->createIndex(['certificate_number' => 1], ['unique' => false, 'background' => true, 'socketTimeoutMS' => -1]);
    }

    public function create($record)
    {
        $this->collection->insert($record, ['socketTimeoutMS' => -1]);
    }

    public function find($id)
    {
        $where = ['certificate_number' => ['$regex' => new \MongoRegex("/^$id/i")]];

        return $this->collection->findOne($where, ['_id' => 0]);
    }

    public function clear()
    {
        $this->collection->remove([], ['socketTimeoutMS' => -1]);
    }
}
