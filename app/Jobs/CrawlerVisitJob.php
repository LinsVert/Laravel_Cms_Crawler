<?php

namespace App\Jobs;

use App\Models\CrCrawlerVisitModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlerVisitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $visit_urls = [];
    protected $visit = null;

    /**
     * Create a new job instance.
     *
     * @param CrCrawlerVisitModel $visit
     * @param array $visit_urls
     */
    public function __construct(CrCrawlerVisitModel $visit, $visit_urls = [])
    {
        //
       $this->visit_urls = $visit_urls;
       $this->visit = $visit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $visitUrls = [];
        if (! $this->visit_urls) {
            $visitUrls = $this->visit->visit_urls;
        } else {
            $visitUrls = $this->visit_urls;
        }
        $visit = $this->visit;
        collect($visitUrls)->map(function ($value) use ($visit) {

        });
    }
}
