<?php

namespace App\Traits;

use GuzzleHttp\Client;

/**
 * 请求trait 对GuzzleHttp 进行高级封装
 */
trait RequestTrait
{
    use SingletonTrait;

    protected static $client = '';

    public static $cookies = [];
    public static $headers = [];
    public static $useragents = [];
    public static $proxies = [];
    public static $content = "";
    public static $status_code = 0;
    public static $error = "";                             

    public static function request($url, $method = 'GET', $param = [], $allow_redirects = true)
    {
        return self::$client->request($method, $url, $param);
    }

    /**
     * 初始化
     */
    public static function init()
    {
        if (!self::$client) {
            self::$client = new Client();
        }
    }   
}
