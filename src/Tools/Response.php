<?php
namespace Itxiao6\Http\Tools;
/**
 * 响应类
 * Class Response
 * @package Itxiao6\Http\Tools
 */
class Response
{
    /**
     * 响应
     * @var null|object
     */
    protected $response = null;

    /**
     * 响应构造器
     * @param $response
     */
    public function __construct($response)
    {
        $this -> response = $response;
    }

    /**
     * 结束响应
     * @param $data
     * @param string $charset
     */
    public function end($data = '',$charset = 'utf-8')
    {
        if(PHP_SAPI == 'cli'){
            # 设置编码
            $this -> response -> header("Content-Type", "text/html;charset=".$charset);
            # 响应内容
            $this -> response -> end($data);
        }else{
            # 设置编码
            header('Content-Type:text/html;charset='.$charset);
            # 响应内容
            echo $data;exit();
        }
    }

    /**
     * 启用Http GZIP压缩
     * 压缩可以减小HTML内容的尺寸，有效节省网络带宽，提高响应时间。必须在write/end发送内容之前执行gzip，否则会抛出错误。
     * $level 压缩等级，范围是1-9，等级越高压缩后的尺寸越小，但CPU消耗更多。默认为1
     * 调用gzip方法后，底层会自动添加Http编码头，PHP代码中不应当再行设置相关Http头
     * @param int $level
     */
    public function gzip($level = 1)
    {
        if(PHP_SAPI == 'cli'){
            $this -> response -> gzip($level);
        }else{
            if( !headers_sent() && // 如果页面头部信息还没有输出
                extension_loaded("zlib") && // 而且php已经加载了zlib扩展
                strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip")) //而且浏览器接受GZIP
            {
                ini_set('zlib.output_compression', 'On');
                ini_set('zlib.output_compression_level', $level);
            }
        }
    }

    /**
     * 向浏览器发送文件
     * @param $filename
     */
    public function send_file($filename)
    {
        if(PHP_SAPI == 'cli'){
            $this -> response -> sendfile($filename);
        }else{
            exit(file_get_contents($filename));
        }
    }

    /**
     * 响应内容
     * @param $data
     * @param string $charset
     */
    public function write($data,$charset = 'utf-8')
    {
        if(PHP_SAPI == 'cli'){
            # 设置编码
            $this -> response -> header("Content-Type", "text/html;charset=".$charset);
            $this -> response -> write($data);
        }else{
            echo $data;
        }
    }

    /**
     * 打印内容
     * @param \Closure|null $func
     */
    public function dump($func = null)
    {
        if(PHP_SAPI == 'cli'){
            # 开启输出缓存区
            ob_start();
            # 执行打印
            $func();
            # 响应缓存区内容
            $this -> write(ob_get_clean());
        }else{
            $func();
        }
    }

    /**
     * 发送状态码
     * @param int $code
     */
    public function status($code = 200)
    {
        if(PHP_SAPI == 'cli'){
            $this -> response -> status($code);
        }else{
            self::send_http_status($code);
        }
    }

    /**
     * 获取响应
     * @return null|object
     */
    public function get_response()
    {
        return $this -> response;
    }
    /**
     * 设置cookie
     */
    public function set_cookie()
    {
        if(PHP_SAPI === 'cli'){
            return $this -> response -> cookie(...func_get_args());
        }else{
            return setcookie(...func_get_args());
        }
    }
    /**
     * 发送HTTP状态
     * @param integer $code 状态码
     * @return void
     */
    protected static function send_http_status($code) {
        $status = array(
            # Informational 1xx
            100 => 'Continue',
            101 => 'Switching Protocols',
            # Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            # Redirection 3xx
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Moved Temporarily ',  # 1.1
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            # 306 is deprecated but reserved
            307 => 'Temporary Redirect',
            # Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            # Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded'
        );
        if(isset($status[$code])) {
            header('HTTP/1.1 '.$code.' '.$status[$code]);
            # 确保FastCGI模式下正常
            header('Status:'.$code.' '.$status[$code]);
        }
    }
}