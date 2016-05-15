<?php

namespace CDM\Test;

class MongoTestCase extends TestCase {

	public function setUp()
	{
		parent::setUp();
		$this->app->make('MongoDB')->drop();
	}
}
