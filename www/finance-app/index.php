<?php
session_write_close();

require_once("./autoload.php");

use Route\Router;
use Route\Request;

Router::start(new Request());
