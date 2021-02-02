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
    return response("jra api");
});

// Auth
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');
    $router->post('logout', 'AuthController@out');
});
// Home
$router->group(['prefix' => 'home'], function () use ($router) {
    $router->get('banner', 'HomeController@banner');
    $router->get('news', 'HomeController@news');
});
// User
$router->group(['prefix' => 'user'], function () use ($router) {
    $router->post('search', 'UserController@search');
    $router->post('detail', 'UserController@detail');
    $router->post('update', 'UserController@update');
    $router->post('password', 'UserController@password');
    $router->get('me', 'UserController@me');
    $router->post('praktisi', 'UserController@praktisi');
});

$router->group(['prefix' => 'article'], function () use ($router) {
    $router->get('', 'ArticleController@index');
});

$router->group(['prefix' => 'admin'], function () use ($router) {
    $router->get('pc', 'AdminController@city');
    $router->get('pw', 'AdminController@province');
    $router->get('main', 'AdminController@pusat');
});

$router->group(['prefix' => 'region'], function () use ($router) {
    $router->get('provinsi', 'RegionController@getProvinsi');
    $router->get('kabupaten', 'RegionController@district');
    $router->get('kecamatan', 'RegionController@getKecamatan');
    $router->get('kabupaten/{id}', 'RegionController@getKabupaten');
});