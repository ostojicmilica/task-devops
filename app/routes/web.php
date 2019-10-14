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

$router->get('/', function () use ($router) {
    return 'Hello Travian';
});

$router->group(['middleware' => 'BasicAuth', 'prefix' => 'api/v1'], function () use ($router) {
    $router->get('/rate', 'ExchangeRateApiController@getRate');
    $router->get('/auth', function () use ($router) {
        return 'authenticated';
    });
});
