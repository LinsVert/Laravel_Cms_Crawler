<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrCrawlerContentModel extends Model
{
    use SoftDeletes;

    protected $table = 'crawler_content';

    protected $fillable = ['content_name', 'content_regx', 'content_regx_type', 'save_flag', 'visit_id'];

    public function visit()
    {
        return $this->hasOne(CrCrawlerVisitModel::class, 'id', 'visit_id');
    }
}
