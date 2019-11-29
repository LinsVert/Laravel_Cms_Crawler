<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\CrawlerService;

/**
 * 爬虫队列调用器
 */
class CrawlerQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dispatch; 
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dispatch)
    {
        //
        $this->dispatch = $dispatch;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dispatch = $this->dispatch;
        if (!$dispatch) {
            return false;
        }
        switch ($dispatch['type']) {
            case 'list':
                CrawlerService::list_crawler($dispatch);
                break;
            case 'content':
                CrawlerService::content_crawler($dispatch);
                break;
        }
    }
}
