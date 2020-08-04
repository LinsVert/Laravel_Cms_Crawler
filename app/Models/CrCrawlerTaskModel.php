<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrCrawlerTaskModel extends Model
{
    use SoftDeletes;
    protected  $table = 'crawler_task';

    public function visit()
    {
        return $this->hasOne(CrCrawlerVisitModel::class, 'id', 'begin_visit_id');
    }

}
