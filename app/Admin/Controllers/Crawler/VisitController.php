<?php


namespace App\Admin\Controllers\Crawler;


use App\Models\CrCrawlerContentModel;
use App\Models\CrCrawlerVisitModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class VisitController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Visit';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CrCrawlerVisitModel());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('visit_name', 'Visit Name');
        $grid->column('visit_urls', 'Visit Urls')->display(function ($urls) {
            if (!$urls) {
                return null;
            }
            $display = '';
            $keys = ['primary', 'info', 'warning', 'success'];
            foreach ($urls as $value) {
                $randoms = rand(0, 3);
                $display .= "<span class='label label-{$keys[$randoms]}'>{$value}</span> ";
            }
            return $display;
        });
        $grid->column('visitHasContent', 'VisitContent')->display(function ($contents){
            if (!$contents) {
                return null;
            }
            $display = '';
            $keys = ['primary', 'info', 'warning', 'success'];
            foreach ($contents as $value) {
                $randoms = rand(0, 3);
                $display .= "<span class='label label-{$keys[$randoms]}'>{$value['content_name']}</span> ";
            }
            return $display;
        });
        $grid->actions(function ($actions) {
            // 去掉查看
            $actions->disableView();
        });
        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('visit_name', 'Visit Name');
            $filter->like('visitHasContent.content_name', 'Visit Content Name');
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
        $form = new Form(new CrCrawlerVisitModel());
        $form->display('id', __('ID'));
        $form->text('visit_name', 'Visit Name');
        $form->list('visit_urls', 'Visit Urls');
        $form->multipleSelect('visitHasContent', 'Visit Content')->options(CrCrawlerContentModel::all()->pluck('content_name', 'id'));
        return $form;
    }
}
