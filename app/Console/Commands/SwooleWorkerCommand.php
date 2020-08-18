<?php


namespace App\Console\Commands;


use App\Worker\SwooleWorker;
use Illuminate\Console\Command;

class SwooleWorkerCommand extends Command
{
    protected $signature = "crawler:websocket";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $worker = new SwooleWorker();
        $worker->start();
    }
}
