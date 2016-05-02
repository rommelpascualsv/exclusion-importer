<?php namespace App\Repositories;

use MongoDB;

class TaxonomyRepository implements Repository 
{
	protected $collection;

	public function __construct(MongoDB $client)
	{
		$this->collection = $client->taxonomy;
		$this->collection->ensureIndex(['code' => 1], ['unique' => true]);
	}

	public function create($record)
	{
		$this->collection->insert($record);
	}

	public function find($code)
	{
		return $this->collection->findOne(['code' => $code], array('_id' => 0));
	}

	public function all() {
		return iterator_to_array($this->collection->find());
	}

	public function clear()
	{
		$this->collection->drop();
	}
}
