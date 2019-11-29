<?php

namespace App\Services;
use App\Model\CrawlerModel;
use App\Traits\CrawlerTrait;
/**
 * CrawlerService 具体实现
 */
class CrawlerService {

    use CrawlerTrait;

    /**
     * 默认启动 启动模式为CrawlerModel
     */
    public static function run($crawler)
    {
        $crawler = CrawlerModel::find((int)$crawler);
        if (!$crawler) {
            return false;
        }
        //校验频率
        if ($crawler->isLoop == 0 && $crawler->runTimes >= 1) {
            return false;
        }
        $crawler_config = $crawler->crawler_config;
        if (!$crawler_config) {
            return false;
        }
        self::init_crawler($crawler_config);
        self::start();
    }

    public static function content_crawler($dispath)
    {
        self::init_crawler($dispath['crawlerConfig']);
        self::content($dispath['url']);
    }

    public static function list_crawler($dispath)
    {
        self::init_crawler($dispath['crawlerConfig']);
        self::list($dispath['url']);
    }

    // public static function parse()
    // {
    //     self::parse();
    // }










}