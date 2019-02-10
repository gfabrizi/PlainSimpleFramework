<?php
require __DIR__ . "/../framework/bootstrap.php";

use gfabrizi\PlainSimpleFramework\Http\Router;
use gfabrizi\PlainSimpleFramework\Http\Request;

$router = new Router(new Request());

$router->get('/', 'HomeController@index');
