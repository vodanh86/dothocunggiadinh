<?php

namespace App\Admin\Controllers;

use App\Models\ContactModel;
use App\Models\DeliverySystemModel;
use App\Models\ProductGroupModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ADeliverySystemController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý hệ thống phân phối';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DeliverySystemModel());
//        dd(Admin::user()->username);
        $grid->column('branch.branch_name', __('Tên chi nhánh'));
        $grid->column('phone_number', __('Số điện thoại'));
        $grid->column('email', __('Email'));
        $grid->column('address', __('Địa chỉ'));
        $grid->column('address_map', __('Địa chỉ trên bản đồ'));
        $grid->column('order', __('Thứ tự'));
        $grid->column('note', __('Ghi chú'));
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'));
//        $grid->model()->where('type', 1)
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
        $show = new Show(DeliverySystemModel::findOrFail($id));
        $show->field('branch.branch_name', __('Tên chi nhánh'));
        $show->field('phone_number', __('Số điện thoại'));
        $show->field('email', __('Email'));
        $show->field('address', __('Địa chỉ'));
        $show->field('address_map', __('Địa chỉ trên bản đồ'));
        $show->field('order', __('Thứ tự'));
        $show->field('note', __('Ghi chú'));
        $show->field('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
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

        $form = new Form(new DeliverySystemModel());
//        $form->hidden('business_id')->value($business->id);
        if ($form->isEditing()) {
            $id = request()->route()->parameter('contact');
            $branchId = $form->model()->find($id)->getOriginal("branch_id");
            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->default($branchId);
        }
        else {
            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->required();
        }
        $form->text('phone_number', __('Số điện thoại'));
        $form->email('email', __('Email'));
        $form->text('address', __('Địa chỉ'));
        $form->text('address_map', __('Địa chỉ trên bản đồ'));
        $form->number('order', __('Thứ tự'));
        $form->text('note', __('Ghi chú'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

        return $form;
    }
}
