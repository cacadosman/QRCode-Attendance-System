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

$router->get('/', ["middleware" => "lecturer", function () use ($router) {
    return $router->app->version();
}]);

$router->post('/auth', 'AuthController@authenticate');

$router->group([
    'prefix' => 'students',
    'middleware' => 'student'
], function () use ($router) {
    $router->post('/courses', 'MahasiswaController@courses');
    $router->post('/presences', 'MahasiswaController@presences');
    $router->post('/notifications', 'MahasiswaController@notifications');
});

$router->group([
    "prefix" => 'lecturers',
    'middleware' => 'lecturer'
], function () use ($router) {
    $router->get('/courses',"DosenController@courses");
    $router->get('/schedules',"DosenController@classSchedules");
    $router->post('/sessions',"DosenController@createSession");
    $router->post('/generate', 'DosenController@generateQr');
});
