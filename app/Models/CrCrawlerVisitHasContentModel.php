<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CrCrawlerVisitHasContentModel extends Model
{
    protected $table = 'crawler_visit_has_content';

    protected $primaryKey = null;

    protected $fillable = ['visit_id', 'content_id'];

}
