<?php


namespace App\Admin\Controllers\Crawler;

use App\Models\CrCrawlerTaskModel;
use App\Models\CrCrawlerVisitModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
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
        $grid->column('task_name', 'Task Name');
        $grid->column('visit.visit_name', 'Begin Visit')->display(function ($value) {
            if (!$value) {
                return "<span class='label label-warning'>Not Select</span>";
            }
            return "<span class='label label-info'>{$value}</span>";
        });
        $grid->column('crontab', 'Crontab');
        $states = [
            'on'  => ['value' => 1, 'text' => 'Allow', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'Not Allow', 'color' => 'danger'],
        ];
        $grid->column('task_queue', 'Used Queue')->editable('select', ['redis' => 'redis', 'mysql' => 'mysql']);
        $grid->column('flow_robot', 'Allow Robot')->switch($states);
        $states2 = [
            'on'  => ['value' => 1, 'text' => 'Enable', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'Disable', 'color' => 'danger'],
        ];
        $grid->column('status', 'Status')->switch($states2);
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->actions(function ($actions) {
            // 去掉查看
            $actions->disableView();
        });
        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('task_name', 'Task Name');
            $filter->like('visit.visit_name', 'Begin Visit Name');
        });
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CrCrawlerTaskModel());

        $form->display('id', __('ID'));
        $form->text('task_name', 'Task Name');
        $form->select('begin_visit_id', 'Begin Visit')->options(CrCrawlerVisitModel::all()->pluck('visit_name', 'id'));
        $queue = [
            'redis' => 'redis',
            'mysql' => 'mysql',
        ];
        $form->select('task_queue', 'Used Queue')->options($queue);
        $form->text('crontab', 'Crontab')->help('See laravel schedule');
        $form->switch('flow_robot', 'Allow Robot');
        $form->switch('status', 'Status');
        $form->footer(function ($footer) {

            // 去掉`重置`按钮
            $footer->disableReset();

            // 去掉`提交`按钮
//            $footer->disableSubmit();

            // 去掉`查看`checkbox
            $footer->disableViewCheck();

            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();

            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();

        });
        return $form;
    }
}
