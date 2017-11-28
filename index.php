<?php
require __DIR__.'/vendor/autoload.php';
# 获取SWOOLE 实例
$http_server = \Itxiao6\Http\Http::get_swoole('127.0.0.1',9501);
# 设置静态文档根目录
$http_server -> set_param('document_root',__DIR__.'/html/');
# 启动HTTP SERVER
$http_server -> start(function($request,$response){
    # 打印数据
//    $response -> dump(function() use($request){
//        echo "<pre>";
//        var_dump($request);
//        echo "</pre>";
//    });
    # 分段响应数据1
    $response -> write('测试内容1<br>');
    # 分段响应数据2
    $response -> write('测试内容2<br>');
    # 响应结束
    $response -> end();
});