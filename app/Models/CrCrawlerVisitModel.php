<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CrCrawlerVisitModel extends Model
{
    protected $table = 'crawler_visit';

    protected $casts = [
        'visit_urls' => 'json',
    ];
}
