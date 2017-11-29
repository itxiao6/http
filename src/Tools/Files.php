<?php
namespace Itxiao6\Http\Tools;
/**
 * 文件上传请求
 * Class Files
 * @package Itxiao6\Http\Tools
 */
class Files
{
    /**
     * 文件数据
     * @var null|object|array
     */
    protected $files = null;

    /**
     * Files 构造器
     * @param $data
     */
    public function __construct($data)
    {
        $this -> files = $data;
    }
    public function get_files()
    {
        return $this -> files;
    }
}