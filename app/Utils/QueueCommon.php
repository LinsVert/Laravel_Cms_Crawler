<?php


namespace App\Utils;


use App\Jobs\CrawlerVisitJob;

class QueueCommon
{
    public static function dispatch($job, $dispatchData = [], $dispatchNow = false, $queue = '') {
        switch ($job) {
            case $job == CrawlerVisitJob::class:
                    if (! $queue) {
                        $queue = 'crawler_visit';
                        $job::dispatch($dispatchData[0])->onQueue($queue);
                    }
                break;
        }

    }
}
