<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

# exclusion_importer routes
Route::get('import/createOldTables', 'ImportController@createOldTables');
Route::get('import/{listPrefix}', 'ImportController@import');
Route::get('import', 'ImportController@index');
