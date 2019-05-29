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

$router->get('/courses',"DosenController@courses");
$router->get('/attendances',"DosenController@classAttendances");
$router->get('/schedules',"DosenController@classSchedules");
$router->post('/sessions',"DosenController@createSession");
$router->post('/generate', 'DosenController@generateQr');
