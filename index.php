<?php

require './vendor/autoload.php';

$http_server = new \Itixao6\Http\Tools\Swoole('127.0.0.1',9501);
$http_server -> start();