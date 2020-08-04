<?php


namespace App\Admin\Controllers\Crawler;

use App\Models\CrCrawlerTaskModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;

class TaskController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Tasks';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CrCrawlerTaskModel());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('task_name');
        $grid->column('flow_robot');
        $grid->column('task_queue');
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        return $grid;
    }
}
