<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * crawler 配置库
 */
class CrawlerConfigModel extends Model
{
    protected $table = 'crawler_config';

    protected $casts = [
        'content_url_rule' => 'json',
        'content_rule' => 'json',
        'list_url_rule' => 'json',
        'cookies' => 'json',
    ];
}
