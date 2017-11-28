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
     * @var null|object
     */
    protected $request = null;
    /**
     * get 参数对象
     * @var null|object
     */
    protected $get = null;
    /**
     * cookie 数据
     * @var null|object
     */
    protected $cookie = null;
    /**
     * 文件上传组
     * @var null|object
     */
    protected $files = null;
    /**
     * Server 对象
     * @var Server|null
     */
    protected $server = null;
    /**
     * 实例化post 参数
     * @var null|object
     */
    protected $post = null;
    /**
     * 实例化请求头
     * @var null|object
     */
    protected $header = null;

    /**
     * 构造方法
     * Request constructor.
     * @param $request
     */
    public function __construct($request)
    {
        if(PHP_SAPI == 'cli'){
            # 实例化GET 参数
            $this -> get = new Get($request -> get);
            # 实例化Server
            $this -> server = new Server($request -> server);
            # 实例化post
            $this -> post = new Post($request -> post);
            # 实例化请求头
            $this -> header = new Header($request -> header);
            # 实例化cookie
            $this -> cookie = new Cookie($request -> cookie);
            # 获取请求
            $this -> request = $request;
        }else{
            # 实例化GET 参数
            $this -> get = new Get($_GET);
            # 实例化Server
            $this -> server = new Server($_SERVER);
            # 实例化post
            $this -> post = new Post($_POST);
            if (!function_exists('getallheaders'))
            {
                foreach ($_SERVER as $name => $value)
                {
                    if (substr($name, 0, 5) == 'HTTP_')
                    {
                        $_headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }
            }
            # 实例化请求头
            $this -> header = new Header($_headers);
            # 实例化cookie
            $this -> cookie = new Cookie($_COOKIE);
            # 获取请求
            $this -> request = $_REQUEST;
        }
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