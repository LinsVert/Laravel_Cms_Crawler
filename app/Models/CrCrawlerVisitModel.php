<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CrCrawlerVisitModel extends Model
{
    protected $table = 'crawler_visit';

    protected $fillable = ['visit_name', 'visit_urls'];

    protected $casts = [
        'visit_urls' => 'json',
    ];

    public function visitHasContent()
    {
        return $this->belongsToMany(CrCrawlerContentModel::class,CrCrawlerVisitHasContentModel::class, 'visit_id', 'content_id', 'id', 'id');
    }
}
