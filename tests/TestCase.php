<?php

class TestCase extends Laravel\Lumen\Testing\TestCase 
{
	/**
	 * Creates the application.
	 *
	 * @return \Laravel\Lumen\Application
	 */
	public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function setUp()
    {
        parent::setUp();

        require __DIR__ . '/factories/nppes/record.php';
        require __DIR__ . '/factories/nppes/row.php';
        require __DIR__ . '/factories/nppes/deactivation_record.php';
        require __DIR__ . '/factories/nppes/deactivation_row.php';
        require __DIR__ . '/factories/taxonomy/row.php';
        require __DIR__ . '/factories/taxonomy/record.php';
        require __DIR__ . '/factories/njna/row.php';
        require __DIR__ . '/factories/njna/record.php';
    }
}
