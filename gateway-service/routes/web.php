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
    'prefix' => 'generator'
], function () use ($router) {
    $router->post('/generate', 'GeneratorController@generate');

});
