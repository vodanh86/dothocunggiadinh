<?php

namespace App\Admin\Controllers;

use App\Models\CommunicationModel;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CommunicationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'CommunicationModel.php';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CommunicationModel);
//        dd(Admin::user()->username);
        $grid->column('id', __('ID'))->sortable();
        $grid->column('name', __('name'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
//        $grid->model()->where('type', 1)
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
        $show = new Show(CommunicationModel::findOrFail($id));

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
        $form = new Form(new CommunicationModel);

        $form->display('id', __('ID'));
        $form->display('created_at', __('Created At'));
        $form->display('updated_at', __('Updated At'));

        return $form;
    }
}
