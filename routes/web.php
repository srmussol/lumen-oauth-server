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
    return $router->app->version();
});

$router->get('/users/', 'UserController@index');
$router->post('/users/', 'UserController@store');
$router->get('/users/{user_id}', 'UserController@show');
$router->put('/users/{user_id}', 'UserController@update');
$router->delete('/users/{user_id}', 'UserController@destroy');

$router->get('/customers/', 'CustomerController@index');
$router->post('/customers/', 'CustomerController@store');
$router->get('/customers/{customer_id}', 'CustomerController@show');
$router->put('/customers/{customer_id}', 'CustomerController@update');
$router->delete('/customers/{customer_id}', 'CustomerController@destroy');