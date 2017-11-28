<?php
namespace Itixao6\Http\Tools;
use Itxiao6\Http\Tools\Request;
use Itxiao6\Http\Tools\Response;

/**
 * Swoole 扩展
 * Class Swoole
 * @package Itixao6\Http\Tools
 */
class Swoole
{
    /**
     * Server
     * @var null | object
     */
    protected static $server = null;
    /**
     * 请求
     * @var null|object
     */
    protected static $request = null;
    /**
     * 响应
     * @var null|object
     */
    protected static $response = null;
    /**
     * reactor线程数
     * 通过此参数来调节主进程内事件处理线程的数量，以充分利用多核。默认会启用CPU核数相同的数量。
     * 一般设置为CPU核数的1-4倍，在swoole中reactor_num最大不得超过CPU核数*4
     * 参考:https://wiki.swoole.com/wiki/page/281.html
     * @var int
     */
    protected static $reactor_num = 2;
    /**
     * 启动的worker进程数。
     * 业务代码是全异步非阻塞的，这里设置为CPU的1-4倍最合理
     * 业务代码为同步阻塞，需要根据请求响应时间和系统负载来调整
     * 参考:https://wiki.swoole.com/wiki/page/275.html
     * @var int
     */
    protected static $worker_num = 0;
    /**
     * worker进程的最大任务数
     * 默认为0，一个worker进程在处理完超过此数值的任务后将自动退出，进程退出后会释放所有内存和资源。
     * 这个参数的主要作用是解决PHP进程内存溢出问题。PHP应用程序有缓慢的内存泄漏，但无法定位到具体原因、无法解决，可以通过设置max_request解决。
     * 参考:https://wiki.swoole.com/wiki/page/p-max_request.html
     * @var int
     */
    protected static $max_request = 10000;
    /**
     * 服务器程序，最大允许的连接数。
     * 如max_conn => 10000, 此参数用来设置Server最大允许维持多少个tcp连接。超过此数量后，新进入的连接将被拒绝。
     * 参考:https://wiki.swoole.com/wiki/page/282.html
     * @var int
     */
    protected static $max_connection = 10000;
    /**
     * Task进程的数量
     * 配置此参数后将会启用task功能。所以Server务必要注册onTask、onFinish2个事件回调函数。如果没有注册，服务器程序将无法启动。
     * Task进程是同步阻塞的，配置方式与Worker同步模式一致。
     * 参考:https://wiki.swoole.com/wiki/page/276.html
     * @var int
     */
    protected static $task_worker_num = 10000;
    /**
     * task进程与worker进程之间通信的方式。
     * 1, 使用unix socket通信，默认模式
     * 2, 使用消息队列通信
     * 3, 使用消息队列通信，并设置为争抢模式
     * 参考:https://wiki.swoole.com/wiki/page/296.html
     * @var int
     */
    protected static $task_ipc_mode = 1;
    /**
     * task进程的最大任务数
     * 一个task进程在处理完超过此数值的任务后将自动退出。这个参数是为了防止PHP进程内存溢出。如果不希望进程自动退出可以设置为0。
     * 参考:https://wiki.swoole.com/wiki/page/295.html
     * @var int
     */
    protected static $task_max_request = 0;
    /**
     * task的数据临时目录
     * 在swoole_server中，如果投递的数据超过8192字节，将启用临时文件来保存数据。这里的task_tmpdir就是用来设置临时文件保存的位置。
     * 参考:https://wiki.swoole.com/wiki/page/314.html
     * @var int|string
     */
    protected static $task_tmpdir = '/tmp';

    /**
     * 数据包分发策略 (
     * 1，轮循模式，收到会轮循分配给每一个worker进程,
     * 2，固定模式，根据连接的文件描述符分配worker。这样可以保证同一个连接发来的数据只会被同一个worker处理
     * 3，抢占模式，主进程会根据Worker的忙闲状态选择投递，只会投递给处于闲置状态的Worker
     * 4，IP分配，根据客户端IP进行取模hash，分配给一个固定的worker进程。可以保证同一个来源IP的连接数据总会被分配到同一个worker进程。算法为 ip2long(ClientIP) % worker_num
     * UID分配，需要用户代码中调用 $serv-> bind() 将一个连接绑定1个uid。然后swoole根据UID的值分配到不同的worker进程。算法为 UID % worker_num，如果需要使用字符串作为UID，可以使用crc32(UID_STRING)
     * )
     * 参考:https://wiki.swoole.com/wiki/page/277.html
     * @var int
     */
    protected static $dispatch_mode = 2;
    /**
     * 设置dispatch函数
     * swoole底层了内置了5种dispatch_mode，如果仍然无法满足需求。可以使用编写C++函数或PHP函数，实现dispatch逻辑。
     * 参考:https://wiki.swoole.com/wiki/page/698.html
     * @var null | string
     */
    protected static $dispatch_func = null;
    /**
     * swoole错误日志文件
     * 在swoole运行期发生的异常信息会记录到这个文件中。默认会打印到屏幕。
     * 参考:https://wiki.swoole.com/wiki/page/280.html
     * @var null | string
     */
    protected static $log_file = null;
    /**
     * 错误日志打印的等级
     * 范围是0-5。低于log_level设置的日志信息不会抛出。
     * 0 =>DEBUG
     * 1 =>TRACE
     * 2 =>INFO
     * 3 =>NOTICE
     * 4 =>WARNING
     * 5 =>ERROR
     * 参考:https://wiki.swoole.com/wiki/page/538.html
     * @var null|int
     */
    protected static $log_level = 0;
    /**
     * SSL证书文件
     * 参考:https://wiki.swoole.com/wiki/page/318.html
     * @var null|string
     */
    protected static $ssl_cert_file = null;
    /**
     * SSL秘钥文件
     * 参考:https://wiki.swoole.com/wiki/page/318.html
     * @var null|string
     */
    protected static $ssl_key_file = null;
    /**
     * worker/task子进程的所属用户
     * 服务器如果需要监听1024以下的端口，必须有root权限。但程序运行在root用户下，代码中一旦有漏洞，攻击者就可以以root的方式执行远程指令，风险很大。配置了user项之后，可以让主进程运行在root权限下，子进程运行在普通用户权限下。
     * 参考:https://wiki.swoole.com/wiki/page/370.html
     * @var null|string
     */
    protected static $user = null;
    /**
     * worker/task子进程的进程用户组
     * 与user配置相同，此配置是修改进程所属用户组，提升服务器程序的安全性。
     * 参考:https://wiki.swoole.com/wiki/page/371.html
     * @var null|string
     */
    protected static $group = null;
    /**
     * 重定向Worker进程的文件系统根目录
     * 此设置可以使进程对文件系统的读写与实际的操作系统文件系统隔离。提升安全性。
     * 参考:https://wiki.swoole.com/wiki/page/392.html
     * @var null|string
     */
    protected static $chroot = null;
    /**
     * 上传文件临时目录
     * @var null|string
     */
    protected static $upload_tmp_dir = null;
    /**
     * 静态文件目录
     * 在swoole 1.9.17或更高版本可用
     * @var null|string
     */
    protected static $document_root = [
        'document_root' => '/data/webroot/example.com',
        'enable_static_handler' => true,
    ];
    /**
     * GET 参数
     * @var null|array
     */
    protected static $get_data = null;
    /**
     * 服务参数
     * @var null|array
     */
    protected static $server_data = null;
    /**
     * Cookie 参数
     * @var null|array
     */
    protected static $cookie_data = null;
    /**
     * POST 参数
     * @var null
     */
    protected static $post_data = null;
    /**
     * 文件上传参数
     * @var null|array
     */
    protected static $files_data = null;
    /**
     * 监听主机
     * @var string
     */
    protected static $host = '127.0.0.1';
    /**
     * 监听端口
     * @var int
     */
    protected static $port = 9501;
    /**
     * 进程
     * @var string
     */
    protected static $memory_limit = '128M';
    /**
     * WebServer 类 构造方法
     * @param string $host
     * @param int $port
     */
    public function __construct($host = '0.0.0.0',$port=9501)
    {
        # 定义swoole_server_param
        $param = [];
        # 设置主进程永不过期
        set_time_limit(0);
        # 获取监听的主机
        self::$host = $host;
        # 获取监听的端口
        self::$port = $port;
        # 设置PHP运行时 最大内存
        ini_set('memory_limit', self::$memory_limit);
        # 创建server
        self::$server = new \swoole_http_server(self::$host,self::$port);
        # 重定向Worker进程的文件系统根目录
        if(self::$chroot != null){
            $param['chroot'] = self::$chroot;
        }
        # worker/task子进程的进程用户组
        if(self::$group != null){
            $param['group'] = self::$group;
        }
        # worker/task子进程的所属用户
        if(self::$user != null){
            $param['user'] = self::$user;
        }
        # SSL秘钥文件
        if(self::$ssl_key_file != null){
            $param['ssl_key_file'] = self::$ssl_key_file;
        }
        # SSL证书文件
        if(self::$ssl_cert_file != null){
            $param['ssl_cert_file'] = self::$ssl_cert_file;
        }
        # 启动的worker进程数
        if(self::$worker_num > 0){
            $param['worker_num'] = self::$worker_num;
        }
        # reactor线程数
        if(self::$reactor_num > 0){
            $param['reactor_num'] = self::$reactor_num;
        }
        # 设置静态资源目录
        if(is_array(self::$document_root)){
            $param['document_root'] = self::$document_root['document_root'];
            $param['enable_static_handler'] = self::$document_root['enable_static_handler'];
        }
        # 日志文件
        if(self::$log_file != null){
            $param['log_file'] = self::$log_file;
        }
        # 日志级别
        $param['log_level'] = self::$log_level;
        # 上传临时文件夹
        if(self::$upload_tmp_dir != null){
            $param['upload_tmp_dir'] = self::$upload_tmp_dir;
        }
        # 设置参数
        self::$server -> set($param);
        # 启动时执行
        self::$server -> on("start", [&$this,'onStart']);
        # 当服务结束时执行
        self::$server -> on("close", [&$this,'onClose']);
        # 当服务关闭的时候
        self::$server -> on("shutdown", [&$this,'onShutdown']);
        # 监听请求
        self::$server -> on('request',[&$this,'onRequest']);
    }

    /**
     * 启动
     */
    public function start($onRequest = null)
    {
        # 启动server
        self::$server -> start();
    }
    /**
     * 启动时执行
     */
    public function onStart()
    {
        echo "Swoole http server is started at http://".self::$host.":".self::$port."\n";
    }
    # 当服务结束的时候
    public function onClose()
    {
        echo "Swoole http server Close\n";
    }
    # 当服务关闭的时候
    public function onShutdown()
    {
        echo "Swoole http server Shutdown\n";
    }
    /**
     * 请求处理
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response)
    {
        # 实例化请求
        self::$request = new Request($request);
        # 实例化响应
        self::$response = new Response($response);
        # 启动应用
        $this -> app_start(self::$request,self::$response);
    }
    /**
     * 启动应用
     * 框架内继承 重写本方法 即可
     * @param $request
     * @param $response
     */
    public function app_start($request,$response)
    {
        $response -> write('<pre>');
        $response -> dump(function() use ($response,$request){
            var_dump($request);
        });
        $response -> write('</pre>');
        $response -> end();
    }
}
require './vendor/autoload.php';
$http_server = new \Itixao6\Http\Tools\Swoole('127.0.0.1',9501);
$http_server -> start();