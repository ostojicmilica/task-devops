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
//    return $router->app->version();
//    $router->get('/', 'ExchangeRateApiController@index');
    return 'Hello World';
});

$router->get('service', 'ExchangeRateApiController@index');

//
//$router->group(['prefix'=>'api/v1'], function() use($router){
//    return 'Hello World Soheila';
//});
