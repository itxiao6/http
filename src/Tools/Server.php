<?php
namespace Itxiao6\Http\Tools;
/**
 * SERVER 数据
 * Class Server
 * @package Itxiao6\Http\Tools
 */
class Server
{
    /**
     * server 数据
     * @var null|object
     */
    protected $server = null;

    /**
     * Server 构造器
     * @param $data
     */
    public function __construct($data)
    {
        $this -> server = $data;
    }

    /**
     * 获取请求的uri
     */
    public function get_uri()
    {
        return $this -> server['request_uri'];
    }

    /**
     * 获取请求的方法
     */
    public function request_method()
    {
        return $this -> server['request_method'];
    }
}