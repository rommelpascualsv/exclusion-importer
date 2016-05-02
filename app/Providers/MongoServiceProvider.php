<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MongoClient;

class MongoServiceProvider extends ServiceProvider 
{
	public function register()
	{
		$this->app->bind('MongoDB', function() {
			$client = new MongoClient('mongodb://' . getenv('MONGODB_HOST') . ':' . getenv('MONGODB_PORT'));
			$db = getenv('MONGODB_DATABASE');
			return $client->selectDB($db);
		});
	}
}
