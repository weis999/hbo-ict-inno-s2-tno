<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an routerlication.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->router->version();
});

$router->group(['prefix'=>'api/'], function() use($router){

        $router->get('/credentialverifyrequest', 'CredentialVerifyRequestController@index');
        $router->post('/credentialverifyrequest', 'CredentialVerifyRequestController@create');
        $router->get('/credentialverifyrequest/{id}', 'CredentialVerifyRequestController@show');
        $router->put('/credentialverifyrequest/{id}', 'CredentialVerifyRequestController@update');
        $router->delete('/credentialverifyrequest/{id}', 'CredentialVerifyRequestController@destroy');

});
