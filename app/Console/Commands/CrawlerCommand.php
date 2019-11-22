<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * 爬虫任务调度
 */
class CrawlerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:run {crawler? : The crawler select} {--other= : Otehr options}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawler Run Kernel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //逻辑处理
        $command = $this->arguments();
        $options = $this->options();
        if ($command['crawler'] === null) {
            echo 'Empty Crawler!' . PHP_EOL;
            return false;
        }
        //分配任务 todo
    }
}
