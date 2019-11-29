<?php

namespace App\Traits;

use App\Jobs\CrawlerQueue;
use App\Model\CrawlerConfigModel;
use App\Model\CrawlerResultCacheModel;
use DiDom\Document;
use DiDom\Query;

/**
 * crawler trait
 */
trait CrawlerTrait
{
    use RequestTrait;

    protected static $crawlerConfig = '';

    protected static $success_code = 200;
    protected static $referer = '';
    protected static $cookie = [];
    protected static $host = '';
    protected static $queue_list = 'crawler_list';
    protected static $queue_content = 'crawler_content';
    
    /**
     * 命令调度默认入口 用于基本调度
     */
    public static function start() {
        self::getInstance()::init();
        if (!is_array(self::$crawlerConfig->start_url)) {
            self::$crawlerConfig->start_url = [self::$crawlerConfig->start_url];
        }
        foreach (self::$crawlerConfig->start_url as $key => $value) {
            // $value = 'https://www.hellorf.com/image/search?q=%E6%84%9F%E6%81%A9%E8%8A%82';
            $response = self::getInstance()::request($value);
            if ($response->getStatusCode() != self::$success_code) {
                echo "Response Status Code Not be 200" . PHP_EOL;
                echo "Request Url: " . $value . PHP_EOL;
                echo "Response Body : " . (string) $response->getBody() . PHP_EOL;
                continue;
            }
            self::parse($response, 'start', $value);
        }
    }

    public static function content($url)
    {
        // dd($url);
        self::getInstance()::init();
        $response = self::getInstance()::request($url);
        if ($response->getStatusCode() != self::$success_code) {
            echo "Response Status Code Not be 200" . PHP_EOL;
            echo "Request Url: " . $value . PHP_EOL;
            echo "Response Body : " . (string) $response->getBody() . PHP_EOL;
            return false;
        }
        //内容页是否需要递归发现 todo
        // self::parse($response, 'content', $url);
        $rule = self::$crawlerConfig->content_rule;
        if (!is_array($rule)) {
            $rule = [$rule];
        }
        $selector = strtoupper(self::$crawlerConfig->content_selector);
        $contents = $response->getBody()->getContents();
        $document = new Document($contents);
        $result = [];
        foreach ($rule as $key => $value) {
            // $result[] = $document->find($value, $selector);
            $_result = $document->find($value, $selector);
            if ($_result) {
                foreach ($_result as $kk => $vv) {
                    $result[$key][] = $vv->text();
                }
            } else {
                $result[] = [];
            }
        }
        $database = self::$crawlerConfig->download_type;
        if (self::$crawlerConfig->isDownload == 1) {
            //存储操作
            //todo
            if ($database == 'mysql') {
                //mysql
                $data = [
                    'config_id' => self::$crawlerConfig->id,
                    'collection_url' => $url,
                    'datas' => json_encode($result, JSON_UNESCAPED_UNICODE),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                CrawlerResultCacheModel::insert($data);
            }
        }
        echo "Cotent Collect Success : " . $url . ' ' . date('Y-m-d H:i:s') . PHP_EOL;
    }
    
    /**
     * list url rule
     */ 
    public static function list($url)
    {
        self::getInstance()::init();
        $response = self::getInstance()::request($url);
        if ($response->getStatusCode() != self::$success_code) {
            echo "Response Status Code Not be 200" . PHP_EOL;
            echo "Request Url: " . $value . PHP_EOL;
            echo "Response Body : " . (string) $response->getBody() . PHP_EOL;
            return false;
        }
        //添加解析
        self::parse($response, 'list', $url);
    }

    /**
     * 解析与分配
     */
    public static function parse(\GuzzleHttp\Psr7\Response $response, $type = 'content', $from = '')
    {
        $contents = $response->getBody()->getContents();
        $document = new Document($contents);
        $links = $document->find("//a/@href", Query::TYPE_XPATH);
        if (!$links) {
            echo 'Paser Url Empty.' . PHP_EOL;
            return false;
        }
        if (!is_array($links)) {
            $links = [$links];
        }
        $links = array_unique($links);
        foreach ($links as $key => $value) {
            $_links_tmp = self::fullUrl($value, $from);
            if ($_links_tmp) {
                $dispatch = [
                    'url' => $_links_tmp,
                    'crawlerConfig' => self::$crawlerConfig,
                ];
                if (self::check_list_url($_links_tmp)) {
                    $dispatch['type'] = 'list';
                    CrawlerQueue::dispatch($dispatch)->onQueue(self::$queue_list);
                }
                if (self::check_content_url($_links_tmp)) {
                    $dispatch['type'] = 'content';
                    CrawlerQueue::dispatch($dispatch)->onQueue(self::$queue_content);
                }
            }
        }    
    }

    public static function check_list_url($url) {
        $result = false;
        //过滤下载类型文件 20180209
        if (preg_match('/\.(zip|7z|cab|rar|iso|gho|jar|ace|tar|gz|bz2|z|xml|pdf|doc|txt|rtf|snd|xls|xlsx|docx|apk|ipa|flv|midi|mps|pls|pps|ppa|pwz|mp3|mp4|mpeg|mpe|asf|asx|mpg|3gp|mov|m4v|mkv|vob|vod|mod|ogg|rm|rmvb|wmv|avi|dat|exe|wps|js|css|bmp|jpg|png|gif|ico|tiff|jpeg|svg|webp|mpa|mdb|bin)$/iu', $url)) {
            return false;
        }
        // if (!empty(self::$configs['list_url_regexes_remove']))
        // {
        //     foreach (self::$configs['list_url_regexes_remove'] as $regex)
        //     {
        //         if (preg_match("#{$regex}#i", $url))
        //         {
        //             return false;
        //         }
        //     }
        // }
        //增加无列表页选项，即所有页面都要抓取内容，包含列表页
        if (empty(self::$crawlerConfig->list_url_rule) or self::$crawlerConfig['list_url_rule'][0] == 'x') {
            return false;
        }
        if (self::$crawlerConfig['list_url_rule'][0] == null) {
            return false;
        }
        //增加泛列表页，即所有页面都是列表页，只抓取链接，不抓取内容
        if (self::$crawlerConfig->list_url_rule[0] == '*') {
            return true;
        }
        if (!empty(self::$crawlerConfig->list_url_rule)) {
            foreach (self::$crawlerConfig->list_url_rule as $regex) {
                if (preg_match("#{$regex}#i", $url)) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }
    public static function check_content_url($url) {
        $result = false;
        //过滤下载类型文件 20180209
        if (preg_match('/\.(zip|7z|cab|rar|iso|gho|jar|ace|tar|gz|bz2|z|xml|pdf|doc|txt|rtf|snd|xls|xlsx|docx|apk|ipa|flv|midi|mps|pls|pps|ppa|pwz|mp3|mp4|mpeg|mpe|asf|asx|mpg|3gp|mov|m4v|mkv|vob|vod|mod|ogg|rm|rmvb|wmv|avi|dat|exe|wps|js|css|bmp|jpg|png|gif|ico|tiff|jpeg|svg|webp|mpa|mdb|bin)$/iu', $url)) {
            return false;
        }

        // if (!empty(self::$configs['content_url_regexes_remove'])) {
        //     foreach (self::$configs['content_url_regexes_remove'] as $regex)
        //     {
        //         if (preg_match("#{$regex}#i", $url))
        //         {
        //             return false;
        //         }
        //     }
        // }
        //增加泛内容模式，即所有页面都要提取内容
        if (empty(self::$crawlerConfig->content_url_rule) or self::$crawlerConfig->content_url_rule[0] == '*')
        {
            return true;
        }
        //无内容，泛列表模式，即所有页面都不提取内容
        if (self::$crawlerConfig->content_url_rule[0] == 'x')
        {
            return false;
        }
        if (!empty(self::$crawlerConfig->content_url_rule)) {
            foreach (self::$crawlerConfig->content_url_rule as $regex) {
                if (preg_match("#{$regex}#i", $url)) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    public static function fullUrl($url, $from)
    {
        //解析来源地址
        $parse_url = @parse_url($from);
        if (empty($parse_url['scheme']) || empty($parse_url['host'])) 
        {
            return false;
        }
        if (!in_array($parse_url['scheme'], array('http', 'https')))
        {
            return false;
        }

        $scheme = $parse_url['scheme'];
        $domain = $parse_url['host'];
        $path = empty($parse_url['path']) ? '' : $parse_url['path'];
        $base_url_path = $domain . $path;
        $base_url_path = preg_replace("/\/([^\/]*)\.(.*)$/", '/', $base_url_path);
        $base_url_path = preg_replace("/\/$/", '', $base_url_path);
        $i = $path_step = 0;
        $dstr = $pstr = '';
        $pos = strpos($url, '#');
        if ($pos > 0) {
            // 去掉#和后面的字符串
            $url = substr($url, 0, $pos);
        }
        // 修正url格式为 //www.jd.com/111.html 为正确的http
        if (substr($url, 0, 2) == '//') {
            $url = preg_replace('/^\/\//iu', '', $url);
        } elseif($url[0] == '/') {
            // /1234.html
            $url = $domain . $url;
        } elseif ($url[0] == '.') {
            if(!isset($url[2])) {
                return false;
            } else {
                $urls = explode('/', $url);
                foreach($urls as $u)
                {
                    if ($u == '..') {
                        $path_step++;
                    } else if($i < count($urls) - 1) {
                        // 遇到 ., 不知道为什么不直接写$u == '.', 貌似一样的
                        $dstr .= $urls[$i] . '/';
                    } else {
                        $dstr .= $urls[$i];
                    }
                    $i++;
                }
                $urls = explode('/',$base_url_path);
                if(count($urls) <= $path_step) {
                    return false;
                }
                else
                {
                    $pstr = '';
                    for($i=0; $i<count($urls) - $path_step; $i++) {
                         $pstr .= $urls[$i] . '/';
                    }
                    $url = $pstr . $dstr;
                }
            }
        } else {
            if (strtolower(substr($url, 0, 7)) == 'http://') {
                $url = preg_replace('#^http://#i', '', $url);
                $scheme = 'http';
            } elseif (strtolower(substr($url, 0, 8)) == 'https://') {
                $url = preg_replace('#^https://#i','',$url);
                $scheme = "https";
            } else {
                 // 相对路径，像 1111.html 这种
                $arr = explode("/", $base_url_path);
                // 去掉空值
                $arr = array_filter($arr);
                $base_url_path = implode("/", $arr);
                $url = $base_url_path . '/' . $url;
            }
        }
        $url = $scheme . '://' . $url;
        return $url;     
    }

    public static function init_crawler($crawlerConfig) {

        self::$crawlerConfig = $crawlerConfig;
        self::$host = $crawlerConfig->host;
        self::$cookie = $crawlerConfig->cookies;
        self::$referer = $crawlerConfig->host;
    }

    // public static function __callStatic($fuc, $args)
    // {
    //     //todo
    // }
}
