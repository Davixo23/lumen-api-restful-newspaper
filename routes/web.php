<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/categories',['uses'=> 'CategoryController@index']);//llamada a metodo controlador de categrias sobre el verbo get
$router->get('/categories/{id}',['uses'=> 'CategoryController@read']);// llamada por id
$router->post('/categories',['uses'=> 'CategoryController@create']);
$router->put('/categories/{id}',['uses'=> 'CategoryController@update']);
$router->patch('/categories/{id}',['uses'=> 'CategoryController@patch']);
$router->delete('/categories/{id}',['uses'=> 'CategoryController@delete']);

// no olvidar que put es para actualizar por completo
// pero patch es para actualziar un parametro solamente o parcialmente