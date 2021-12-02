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
    $router->get('detail/{id}', 'ArticleController@detail');
});

$router->group(['prefix' => 'admin'], function () use ($router) {
    $router->get('pc', 'AdminController@city');
    $router->get('pac', 'AdminController@district');
    $router->get('pw', 'AdminController@province');
    $router->get('main', 'AdminController@pusat');
});

$router->group(['prefix' => 'region'], function () use ($router) {
    $router->get('province', 'RegionController@getProvinsi');
    $router->get('kabupaten', 'RegionController@getKabupaten');
    $router->get('kecamatan', 'RegionController@getKecamatan');
});

$router->group(['prefix' => 'search'], function () use ($router) {
    $router->get('city', 'RegionController@searchCity');
});

$router->group(['prefix' => 'skill'], function () use ($router) {
    $router->get('', 'SkillController@getSkill');
    $router->post('update', 'SkillController@updateSkill');
});

$router->group(['prefix' => 'banner'], function () use ($router) {
    $router->get('', 'BannerController@index');
});

$router->group(['prefix' => 'news'], function () use ($router) {
    $router->get('detail', 'NewsController@detail');
    $router->get('search', 'NewsController@search');
});

$router->group(['prefix' => 'product'], function () use ($router) {
    $router->get('', 'ProductController@index');
    $router->get('detail/{code}', 'ProductController@detail');
});

$router->group(['prefix' => 'social'], function () use ($router) {
    $router->get('', 'SocialController@index');
    $router->get('list/{id}', 'SocialController@list');
});

$router->group(['prefix' => 'testimoni'], function () use ($router) {
    $router->get('', 'TestimoniController@index');
    $router->post('create', 'TestimoniController@store');
});
// Adm
$router->group(['prefix' => 'adm'], function () use ($router) {
    $router->post("suggest", "AdmController@suggest");
    $router->get("bank", "AdmController@bank");
});

$router->post("donation/send", "AdmController@donation");

$router->group(['prefix' => 'schedule'], function () use ($router) {
    $router->get("", "ScheduleController@index");
    $router->post("create", "ScheduleController@store");
    $router->get("detail/{id}", "ScheduleController@show");
});
