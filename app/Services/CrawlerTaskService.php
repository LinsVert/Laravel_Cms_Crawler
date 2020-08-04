<?php


namespace App\Services;


use App\Models\CrCrawlerTaskModel;

class CrawlerTaskService
{
    public function run($taskId) {
        $task = CrCrawlerTaskModel::find($taskId);
        if (!$task) {
            echo 'task not find.' . PHP_EOL;
            return false;
        }
    }
}
