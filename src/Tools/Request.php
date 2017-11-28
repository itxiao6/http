<?php
namespace Itxiao6\Http\Tools;
/**
 * 请求类
 * Class Request
 * @package Itxiao6\Http\Tools
 */
class Request
{
    /**
     * 请求对象
     * @var null
     */
    protected $request = null;

    /**
     * 构造方法
     * Request constructor.
     * @param $request
     */
    public function __construct($request)
    {

//        # 获取GET 数据
//        $this -> get_param($request -> get);
//        # 获取SERVER数据
//        $this -> server_param($request -> server);
//        # 获取POST 数据
//        $this -> post_param($request -> post);
//        # 获取COOKIE 数据
//        $this -> cookie_param($request -> cookie);
//        # 获取上传的文件数据
//        $this -> files_param($request -> files);
        $this -> request = $request;
    }

    /**
     * 设置请求uri
     */
    public function set_rquest_uri()
    {

    }

    /**
     * 设置请求url
     */
    public function set_request_url()
    {

    }

    /**
     * 设置post 数据
     */
    public function set_post_data()
    {

    }

    /**
     * 设置get数据
     */
    public function set_get_data()
    {

    }

    /**
     * 设置cookie数据
     */
    public function set_cookie_data()
    {

    }

    /**
     * 设置上传文件数据
     */
    public function set_file_data()
    {

    }


    /**
     * 获取session实例
     */
    public static function session()
    {

    }
    /**
     * 获取cookie实例
     */
    public static function cookie()
    {

    }

}