<?php
require __DIR__.'/vendor/autoload.php';
$http_server = new \Itxiao6\Http\Tools\Swoole('127.0.0.1',9501);
$http_server -> start();