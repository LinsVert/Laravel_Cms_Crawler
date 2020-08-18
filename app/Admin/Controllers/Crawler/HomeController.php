<?php

namespace App\Admin\Controllers\Crawler;

use App\Http\Controllers\Controller;
use App\Services\CrawlerTaskService;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
//        $this->render();
//        return $content
//            ->title('Dashboard')
//            ->description('Description...')
////            ->row(Dashboard::title())
////            ->row(function (Row $row) {
////
////                $row->column(4, function (Column $column) {
////                    $column->append(Dashboard::environment());
////                });
////
////                $row->column(4, function (Column $column) {
////                    $column->append(Dashboard::extensions());
////                });
////
////                $row->column(4, function (Column $column) {
////                    $column->append(Dashboard::dependencies());
////                });
////            })
//            ->body('<div id="terminal"></div>');

        $path = storage_path('/logs/laravel-2020-08-08.log');
        exec("tail -100 $path", $result);
        $this->render($result);
        $this->test();
        return $content->body('<div id="terminal"></div>');
    }


    public function test() {

    }
    public function render($datas) {
        $datas = json_encode($datas);
        $script = <<<EOT
         Terminal.applyAddon(fit);
         var term = new Terminal();
         var data = $datas;
        term.open(document.getElementById('terminal'));
        term.fit()
        for(i in data) {
            term.writeln(data[i])
        }
EOT;
//        term.write('Hello from \x1B[1;3;31mxterm.js\x1B[0m $ ')
        Admin::script($script);
    }
}
