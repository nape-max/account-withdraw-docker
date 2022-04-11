<?php
session_write_close();

require_once("./autoload.php");

use Route\RouteService;
use Route\RouteParser;

$routeParser = new RouteParser($_SERVER['REQUEST_URI']);

$router = new RouteService();
$result = $router->callAction($routeParser->getSplittedRoute());

echo $result;