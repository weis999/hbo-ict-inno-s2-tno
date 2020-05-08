<?php

use TNO\EssifLab\Application\Controllers\API;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

if(end($uri) === "api"){
    $requestMethod = $_SERVER["REQUEST_METHOD"];

//  pass the request method and user ID to the PersonController and process the HTTP request:
    $controller = new API( $requestMethod);
    $controller->processRequest();
}