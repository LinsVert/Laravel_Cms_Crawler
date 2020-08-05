<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrCrawlerTaskModel extends Model
{
    use SoftDeletes;
    protected  $table = 'crawler_task';

    protected $fillable = ['task_name', 'begin_visit_id', 'crontab', 'flow_robot', 'task_queue', 'status'];

    public function visit()
    {
        return $this->hasOne(CrCrawlerVisitModel::class, 'id', 'begin_visit_id');
    }

}
