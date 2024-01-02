<?php

namespace App\Admin\Controllers;

use App\Models\ContactModel;
use App\Models\ProductGroupModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AContactController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý đăng ký tư vấn';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ContactModel());
        $grid->column('name', __('Họ và tên'));
        $grid->column('phone_number', __('Số điện thoại'));
        $grid->column('email', __('Email'));
        $grid->column('content', __('Sản phẩm quan tâm'));
        $grid->column('reply', __('Trạng thái phản hồi'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Reply", "grid");
        });
        $grid->column('note', __('Ghi chú'));
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
        $show = new Show(ContactModel::findOrFail($id));
        $show->field('name', __('Họ và tên'));
        $show->field('phone_number', __('Số điện thoại'));
        $show->field('email', __('Email'));
        $show->field('content', __('Sản phẩm quan tâm'));
        $show->field('reply', __('Trạng thái phản hồi'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Reply", "grid");
        });
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
        $replyOptions = (new UtilsCommonHelper)->commonCode("Reply", "Status", "description_vi", "value");
        $statusDefault = $statusOptions->keys()->first();
        $replyDefault = $replyOptions->keys()->first();

        $form = new Form(new ContactModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('contact');
        }
        $form->text('name', __('Họ và tên'));
        $form->mobile('phone_number', __('Số điện thoại'));
        $form->email('email', __('Email'));
        $form->text('content', __('Sản phẩm quan tâm'));
        $form->textarea('note', __('Ghi chú'));
        $form->select('reply', __('Trạng thái phản hồi'))->options($replyOptions)->default($replyDefault);
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

        return $form;
    }
}
