<?php

namespace App\Admin\Controllers\Crawler;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Model\CrawlerModel;
use App\Model\CrawlerConfigModel;
class TaskController extends AdminController
{
     /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Crawler Task';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CrawlerModel);

        $grid->column('id', __('ID'))->sortable();
        $grid->column('name', 'Task Name');
        $grid->crontab();
        $grid->crawler_config()->name('Crawler Config Name');
        $grid->isLoop()->display(function ($value) {
            if ($value == 1) {
                return 'Loop';
            }
            if ($value == 0) {
                return 'Once';
            }
            return $value;
        });
        $grid->autoProxy()->display(function ($value) {
            if ($value == 1) {
                return 'Enable';
            }
            if ($value == 0) {
                return 'Disable';
            }
            return $value;
        });
        $grid->isValid()->display(function ($value) {
            if ($value == 1) {
                return 'Enable';
            }
            if ($value == 0) {
                return 'Disable';
            }
            return $value;
        });
        $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));
        return $grid;
    }

     /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(CrawlerModel::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CrawlerModel);

        $form->display('id', __('ID'));
        $form->text('name', 'Task Name');
        //todo
        $form->text('crontab', '调度周期');
        $form->radio('isLoop', '循环调度')->options([0 => 'Once', 1 => 'Loop'])->default(1);
        $form->radio('autoProxy', '自动代理')->options([0 => 'Disabled', 1 => 'Enabled'])->default(0);
        $form->radio('isValid', '是否启用')->options([0 => 'Disabled', 1 => 'Enabled'])->default(1);
        $form->text('logPath', '日志地址')->placeholder('默认是/log/taskId/taskName.log');
        $form->select('config_id', 'Crawler Config')->options(CrawlerConfigModel::all()->pluck('name', 'id')->toArray());



        $form->text('created_at');
        $form->text('updated_at');

        return $form;
    }


}