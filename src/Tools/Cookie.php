<?php
namespace Itxiao6\Http\Tools;
/**
 * Cookie 数据
 * Class Cookie
 * @package Itxiao6\Http\Tools
 */
class Cookie
{
    /**
     * COOKIE 数据
     * @var array|null|object
     */
    protected $cookie = null;
    /**
     * Cookie 构造器
     * @param $data
     */
    public function __construct($data)
    {
        $this -> cookie = $data;
    }


    /**
     * 获取cookie
     * @param null $name
     * @return mixed
     */
    public function get_cookie($name = null)
    {
        if(PHP_SAPI === 'cli'){
            return ($name===null)?$this -> cookie:$this -> cookie[$name];
        }else{
            return ($name===null)?$_COOKIE:(isset($_COOKIE[$name])?$_COOKIE[$name]:null);
        }
    }

}