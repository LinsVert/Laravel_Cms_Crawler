<?php

namespace App\Admin\Controllers\Crawler;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Model\CrawlerConfigModel;

class ConfigController extends AdminController
{
     /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Crawler Config';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CrawlerConfigModel);

        $grid->column('id', __('ID'))->sortable();
        $grid->column('name');
        $grid->isValid('是否启用')->display(function ($value) {
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
        $show = new Show(CrawlerConfigModel::findOrFail($id));

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
        $form = new Form(new CrawlerConfigModel);

        $form->display('id', __('ID'));
        $form->text('name', '名称');

        $form->number('dee  pth', '发现内容页的深度')->min(1)->default(1);
        $form->text('start_url', '开始Url');
        $form->list('cookies', 'cookies');
        $form->list('list_url_rule', '列表页链接发现规则');
        $form->list('content_url_rule', '内容页链接发现规则');
        $form->list('content_rule', '内容页内容获取规则');
        $form->radio('isValid', '启用')->options([0 => 'Disable', 1 => 'Enable'])->default(1);
        $form->radio('fllow_robots', '遵循网站robots')->options([0 => 'No', 1 => 'Yes'])->default(1);
        $form->radio('isDownload', '内容下载')->options([0 => 'No', 1 => 'Yes'])->default(1);
        $form->select('download_type', '存储位置')->options(['mysql' => 'mysql', 'redis' => 'redis'])->default('mysql');
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));

        return $form;
    }

}