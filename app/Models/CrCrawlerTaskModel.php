<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrCrawlerTaskModel extends Model
{
    use SoftDeletes;
    protected  $table = 'crawler_task';

    protected $fillable = ['task_name', 'begin_visit_id', 'crontab', 'flow_robot', 'task_queue', 'status'];

    protected $casts = [
        'crontab' => 'json'
    ];

    public function getCrontabAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setCrontabAttribute($value)
    {
        $this->attributes['crontab'] = json_encode(array_values($value));
    }

    public function visit()
    {
        return $this->hasOne(CrCrawlerVisitModel::class, 'id', 'begin_visit_id');
    }

}
