<?php


namespace App\Services;


use App\Models\CrCrawlerTaskModel;
use Cron\CronExpression;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Arr;

class CrawlerTaskService
{
    const CRONTAB_LIST = [
        'once' => 'once',
        'cron' => 'cron',
        'everyMinute' => 'everyMinute',
        'everyFiveMinutes' => 'everyFiveMinutes',
        'everyTenMinutes' => 'everyTenMinutes',
        'everyFifteenMinutes' => 'everyFifteenMinutes',
        'everyThirtyMinutes' => 'everyThirtyMinutes',
        'hourly' => 'hourly',
        'hourlyAt' => 'hourlyAt',
        'daily' => 'daily',
        'at' => 'at',
        'dailyAt' => 'dailyAt',
        'weekdays' => 'weekdays',
        'weekends' => 'weekends',
        'mondays' => 'mondays',
        'tuesdays' => 'tuesdays',
        'wednesdays' => 'wednesdays',
        'thursdays' => 'thursdays',
        'fridays' => 'fridays',
        'saturdays' => 'saturdays',
        'sundays' => 'sundays',
        'weekly' => 'weekly',
        'weeklyOn' => 'weeklyOn'
    ];
    public $id = null;
    public  function __construct($id = 0)
    {
        $this->id = $id;
    }

    public function run($taskId) {
        $task = CrCrawlerTaskModel::find($taskId);
        if (!$task) {
            echo 'task not find.' . PHP_EOL;
            return false;
        }
        echo "run in this case {$taskId}" . PHP_EOL;
    }

    public static function initTaskRun(Schedule $schedule) {
        $task = CrCrawlerTaskModel::where('status', 1)->where('run_status', '!=', 2)->get();
        foreach ($task as $value) {
            $crontab = $value->crontab;
            $event = $schedule->command("crawler:run {$value->id} --other=todo");
            self::transformCrontab($crontab, $event);
            $event->appendOutputTo(storage_path('logs/test.log'));
            $event->withoutOverlapping();
        }
        foreach ($schedule->events() as $value) {
            echo $value->command . ' | '. $value->expression . PHP_EOL;
        }
    }

    public static function transformCrontab($crontab, Event $event = null) {
        if ($event) {
            foreach ($crontab as $value) {
                $func = Arr::get($value, 'func');
                if ($func == 'once') {
                    continue;
                }
                $values = [Arr::get($value, 'value_1'), Arr::get($value, 'value_2')];
                if (method_exists($event, $func)) {
                    call_user_func_array([$event, $func], $values);
                    continue;
                }
                call_user_func([$event, 'cron'], $func);
            }
           return $event;
        }

        return function ($crontab, Event $event) {
            foreach ($crontab as $value) {
                $func = Arr::get($value, 'func');
                if ($func == 'once') {
                    continue;
                }
                $values = [Arr::get($value, 'value_1'), Arr::get($value, 'value_2')];
                if (method_exists($event, $func)) {
                    call_user_func_array([$event, $func], $values);
                    continue;
                }
                call_user_func([$event, 'cron'], $func);
            }
            return $event;
        };
    }
}
