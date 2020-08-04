<?php

namespace App\Console\Commands;

use App\Jobs\CrawlerVisitJob;
use App\Models\CrCrawlerVisitModel;
use App\Utils\QueueCommon;
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
        $visit = CrCrawlerVisitModel::find(1);
        QueueCommon::dispatch(CrawlerVisitJob::class, [$visit]);
        exit;
        //逻辑处理
        $command = $this->arguments();
        $options = $this->options();
        dd($options);
    }
}
