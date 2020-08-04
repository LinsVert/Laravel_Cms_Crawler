<?php


namespace App\Utils;


use App\Jobs\CrawlerVisitJob;

class QueueCommon
{
    public static function dispatch($job, $dispatchData = [], $dispatchNow = false, $queue = '') {

        switch ($job) {
            case $job instanceof CrawlerVisitJob:
                    if (! $queue) {
                        $queue = 'crawler_visit';
                        $job::dispatch(extract($dispatchData))->onQueue($queue);
                    }
                break;
        }

    }
}
