<?php namespace App\Repositories;

use MongoDB;

class NppesRepository implements Repository 
{
	protected $collection;

	public function __construct(MongoDB $client)
	{
		$this->collection = $client->nppes;
		$this->collection->createIndex(['npi' => 1], ['unique' => false, 'background' => true, 'socketTimeoutMS' => -1]);
	}

	public function create($record)
	{
		$this->collection->insert($record, ['socketTimeoutMS' => -1]);
	}

	public function createOrUpdate($record)
	{
		$this->collection->update(['npi' => $record['npi']], ['$set' => $record], ['upsert' => true, 'socketTimeoutMS' => -1]);
	}

	public function update($record)
	{
		$this->collection->update(['npi' => $record['npi']], ['$set' => $record], ['socketTimeoutMS' => -1]);
	}

	public function find($id)
	{
		return $this->collection->findOne(['npi' => $id], array('_id' => 0));
	}

	public function clear()
	{
		$this->collection->remove([], ['socketTimeoutMS' => -1]);
	}
}
