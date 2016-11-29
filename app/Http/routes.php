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
$app->get('import', 'ExclusionListController@index');
$app->get('exclusion-lists', 'ExclusionListController@index');
$app->post('exclusion-lists/create-old-tables', 'ExclusionListController@createOldTables');
$app->post('exclusion-lists/import/{listPrefix}', 'ExclusionListController@import');
$app->post('exclusion-lists/upload', 'ExclusionListController@upload');

