<?php
namespace Itxiao6\Http;
/**
 * Http 组件入口
 * Class Http
 * @package Itxiao6\Http
 */
class Http
{
    /**
     * 获取Swoole 服务
     * @param $host
     * @param $port
     * @return Tools\Swoole
     */
    public static function get_swoole($host,$port)
    {
        return new \Itxiao6\Http\Tools\Swoole($host,$port);
    }
}