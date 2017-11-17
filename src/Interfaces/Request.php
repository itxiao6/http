<?php
namespace Itxiao6\Http\Interfaces;
/**
 * 请求类接口
 * Interface Request
 * @package Itxiao6\Http\Interfaces
 */
interface Request
{
    /**
     * 获取session实例
     * @return mixed
     */
    public static function session();

    /**
     * 获取cookie实例
     * @return mixed
     */
    public static function cookie();

    /**
     * 设置请求uri
     * @return mixed
     */
    public function set_rquest_uri();

    /**
     * 设置请求url
     * @return mixed
     */
    public function set_rquest_url();

    /**
     * 设置post数据
     * @return mixed
     */
    public function set_post_data();

    /**
     * 设置get数据
     * @return mixed
     */
    public function set_get_data();

    /**
     * 设置文件上传数据
     * @return mixed
     */
    public function set_file_data();

}