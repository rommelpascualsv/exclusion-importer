<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MongoClient;

class MongoServiceProvider extends ServiceProvider 
{
	public function register()
	{
		$this->app->bind('MongoDB', function() {
			$client = new MongoClient();
			$db = getenv('DB_DATABASE');
			return $client->selectDB($db);
		});
	}
}
