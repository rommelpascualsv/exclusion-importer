<?php

namespace App\Repositories;

class NJCredentialRepository implements Repository 
{
	protected $collection;

	public function __construct(\MongoDB $client)
	{
		$this->collection = $client->nj_credential;
		$this->collection->createIndex(['license_number' => 1], ['unique' => false, 'background' => true, 'socketTimeoutMS' => -1]);
	}

	public function create($record)
	{
		$this->collection->insert($record);
	}

	public function clear()
	{
		$this->collection->remove([], ['socketTimeoutMS' => -1]);
	}

	public function find($id)
	{
		$where = ['license_number' => ['$regex' => new \MongoRegex("/^$id/i")]];

		return $this->collection->findOne($where, ['_id' => 0]);
	}

	public function createOrUpdate($record)
	{
		$this->collection->update(['license_number' => $record['license_number']], ['$set' => $record], ['upsert' => true, 'socketTimeoutMS' => -1]);
	}
}
