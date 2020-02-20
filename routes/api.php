<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//In addition to generating resources that transform individual models,
//you may generate resources that are responsible for transforming collections of models.
//This allows your response to include links and other meta information that is relevant to an entire collection
//of a given resource.
Route::group( [ 'namespace' => 'Api' ], function() {
    Route::apiResource('users', 'UserController');
});