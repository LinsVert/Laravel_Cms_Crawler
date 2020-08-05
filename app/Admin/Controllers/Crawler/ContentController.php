<?php


namespace App\Admin\Controllers\Crawler;


use App\Models\CrCrawlerContentModel;
use App\Models\CrCrawlerVisitModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class ContentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Content';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CrCrawlerContentModel());
        $grid->column('id', __('ID'))->sortable();
        $grid->column('content_name', 'Content Name');
        $grid->column('content_regx', 'Content Regx');
        $grid->column('content_regx_type', 'Content Regx Type');
        $grid->column('visit.visit_name', 'Visit Back')->label('success');
        $states = [
            'on'  => ['value' => 1, 'text' => 'Saved', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => 'Not Saved', 'color' => 'danger'],
        ];
        $grid->column('save_flag', 'Saved')->switch($states);
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CrCrawlerContentModel());
        $form->display('id', __('ID'));
        $form->text('content_name', 'Content Name');
        $form->text('content_regx', 'Content Regx');
        $form->select('content_regx_type', 'Content Regx Type')->options([
            'xpath' => 'xpath',
            'regx' => 'regx',
            'css-selector' => 'css-selector'
        ]);
        $form->select('visit_id', 'Visit Back')->options(CrCrawlerVisitModel::all()->pluck('visit_name', 'id'));
        $form->switch('save_flag', 'Saved');
        return $form;
    }
}
