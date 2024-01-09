<?php

namespace App\Admin\Controllers;

use App\Models\ProductGroupModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AProductGroupController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Nhóm sản phẩm';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductGroupModel());
        $grid->column('name', __('Tên nhóm sản phẩm'))->filter('like');
        $grid->column('description', __('Mô tả'))->textarea();
        $grid->column('cover_image', __('Ảnh'))->image();
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $grid->column('created_at', __('Ngày tạo'))->sortable();
        $grid->column('updated_at', __('Ngày cập nhật'))->sortable();
        $grid->model()->orderBy('created_at', 'desc');
        $grid->disableFilter();
        $grid->fixColumns(0, -1);
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ProductGroupModel::findOrFail($id));
        $show->field('name', __('Tên nhóm sản phẩm'));
        $show->field('description', __('Mô tả'));
        $show->field('cover_image', __('Ảnh'))->image();
        $show->field('created_at', __('Ngày tạo'))->sortable();
        $show->field('updated_at', __('Ngày cập nhật'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $statusOptions = (new UtilsCommonHelper)->commonCode("Core", "Status", "description_vi", "value");
        $statusDefault = $statusOptions->keys()->first();

        $form = new Form(new ProductGroupModel);
        if ($form->isEditing()) {
            $id = request()->route()->parameter('product_group');
        }
        $form->text('name', __('Tên nhóm sản phẩm'));
        $form->text('description', __('Mô tả'));
        $form->image('cover_image', __('Hình ảnh'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

        return $form;
    }
}
