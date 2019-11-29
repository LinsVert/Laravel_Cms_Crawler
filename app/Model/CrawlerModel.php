<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * crawler 配置库
 */
class CrawlerModel extends Model
{
    //是否可执行
    const VALID_FLAG = 1;
    const UN_VALID_FLAG = 0;
    
    //是否重复
    
    protected $table = 'crawler';

    public function crawler_config(){
        return $this->hasOne('App\Model\CrawlerConfigModel', 'id', 'config_id');
    }
}
