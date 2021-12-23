<?php

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// API route group
use Laravel\Lumen\Routing\Router;

$router->group(
    ['prefix' => 'api/{locale}/'],
    function () use ($router) {
        $router->post('otp', 'AuthController@otp');
        $router->post('login', 'AuthController@login');
    }
);

$router->group(
    ['middleware' => 'auth'],
    function () use ($router) {

        $router->group(
            ['prefix' => 'api/{locale}/'],
            function () use ($router) {
                $router->post('logout', 'AuthController@logout');
                $router->post('me', 'AuthController@me');
                $router->post('update', 'AuthController@update');
            }
        );

        $router->group(
            ['prefix' => 'api/{locale}/admin'],
            function () use ($router) {
                $router->post('create', 'UserAdminController@create');
                $router->post('edit', 'UserAdminController@edit');
                $router->post('delete', 'UserAdminController@delete');
            }
        );
    }
);


$router->get('/', function () {
    return response()->json(['success' => true, 'message' => 'SSO service is Running!']);
});
