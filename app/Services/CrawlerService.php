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
        if (!crawler) {
            return false;
        }
        $crawler_config = $crawler->crawler_config;
        if (!$crawler_config) {
            return false;
        }
        self::run($crawler_config);
    }

    public static function content()
    {

    }

    public static function list()
    {

    }

    public static function parse()
    {

    }










}