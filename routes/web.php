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

$router->group(['prefix' => 'crawler'], function () use ($router) {
    $router->post('/', 'CrawlController@crawler');
});

$router->group(['prefix' => 'category'], function () use ($router) {
    $router->get('/', 'CategoryController@getAll');
    $router->get('/{id}}', 'CategoryController@getById');
    $router->patch('/update/{id}', 'CategoryController@updateById');
    $router->delete('/delete/{id}', 'CategoryController@delete');
    $router->post('/crawler-sync', 'CategoryController@store');
});

