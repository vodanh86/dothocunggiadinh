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
        $grid->column('name', __('Tên hệ thống phân phối'))->filter('like');
        $grid->column('phone_number', __('Số điện thoại'));
        $grid->column('email', __('Email'));
        $grid->column('address', __('Địa chỉ'));
        $grid->column('address_map', __('Địa chỉ trên bản đồ'));
        $grid->column('order', __('Thứ tự'));
        $grid->column('note', __('Ghi chú'));
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $grid->column('created_at', __('Ngày tạo'))->display(function ($createdAt) {
            return ConstantHelper::dateFormatter($createdAt);
        });
        $grid->column('updated_at', __('Ngày cập nhật'))->display(function ($updatedAt) {
            return ConstantHelper::dateFormatter($updatedAt);
        });
        $grid->model()->orderBy('created_at', 'desc');
//        $grid->disableFilter();
        $grid-> filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $statusOptions = UtilsCommonHelper::findAllStatus("Core","Status","description_vi","value");

            $filter->like('name', 'Tên hệ thống phân phối');
            $filter->like('phone_number', 'Số điện thoại');
            $filter->like('email', 'Email');
            $filter->equal('status', 'Trạng thái')->select($statusOptions);
            $filter->date('created_at', 'Ngày tạo');
            $filter->date('updated_at', 'Ngày cập nhật');
        });
//        $grid->fixColumns(0, 0);
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
        $show->field('name', __('Tên hệ thống phân phối'));
        $show->field('phone_number', __('Số điện thoại'));
        $show->field('email', __('Email'));
        $show->field('address', __('Địa chỉ'));
        $show->field('address_map', __('Địa chỉ trên bản đồ'));
        $show->field('order', __('Thứ tự'));
        $show->field('note', __('Ghi chú'));
        $show->field('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
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

        $form = new Form(new DeliverySystemModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('delivery_system');
        }
        $form->text('name', __('Tên hệ thống phân phối'))->required();
        $form->text('phone_number', __('Số điện thoại'))->required();
        $form->email('email', __('Email'));
        $form->text('address', __('Địa chỉ'))->required();
        $form->text('address_map', __('Địa chỉ trên bản đồ'));
        $form->number('order', __('Thứ tự'));
        $form->text('note', __('Ghi chú'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

        return $form;
    }
}
