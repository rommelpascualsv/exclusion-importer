<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return view('hello');
});

# exclusion_importer routes
$app->get('import/createOldTables', 'ImportController@createOldTables');
$app->get('import/{listPrefix}', 'ImportController@import');
$app->get('import', 'ImportController@index');
$app->post('import/upload', 'ImportController@upload');

