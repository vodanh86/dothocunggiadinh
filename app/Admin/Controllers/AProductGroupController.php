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
//        dd(Admin::user()->username);
        $grid->column('branch.branch_name', __('Tên chi nhánh'));
        $grid->column('name', __('Tên nhóm sản phẩm'));
        $grid->column('description', __('Mô tả'));
        $grid->column('cover_image', __('Ảnh'))->image();
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'));
//        $grid->model()->where('type', 1)
        $grid->fixColumns(0, 0);
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
        $show->field('branch.branch_name', __('Tên chi nhánh'));
        $show->field('name', __('Tên nhóm sản phẩm'));
        $show->field('description', __('Mô tả'));
        $show->field('cover_image', __('Ảnh'))->image();
        $show->field('created_at', __('Created at'))->sortable();
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
        $statusOptions = (new UtilsCommonHelper)->commonCode("Core", "Status", "description_vi", "value");
        $statusDefault = $statusOptions->keys()->first();
        $branchs = (new UtilsCommonHelper)->optionsBranch();
        $business = (new UtilsCommonHelper)->currentBusiness();

        $form = new Form(new ProductGroupModel);
//        $form->hidden('business_id')->value($business->id);
        if ($form->isEditing()) {
            $id = request()->route()->parameter('product_group');
            $branchId = $form->model()->find($id)->getOriginal("branch_id");
            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->default($branchId);
        }
        else {
            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->required();
        }
        $form->text('name', __('Tên nhóm sản phẩm'));
        $form->text('description', __('Mô tả'));
        $form->image('cover_image', __('Hình ảnh'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

        return $form;
    }
}
