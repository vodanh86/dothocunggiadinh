<?php

namespace App\Admin\Controllers;

use App\Models\CommunicationModel;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AEventsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý sự kiện';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CommunicationModel);
//        dd(Admin::user()->username);
//        $grid->column('branch.branch_name', __('Tên chi nhánh'));
        $grid->column('title', __('Tiêu đề'));
        $grid->column('summary', __('Tóm tắt'));
        $grid->column('content', __('Nội dung'))->textarea();
        $grid->column('slug', __('Đường dẫn'));
        $grid->column('start_date', __('Ngày bắt đầu'));
        $grid->column('end_date', __('Ngày kết thúc'));
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('public_date', __('Ngày công khai'));
        $grid->column('author', __('Tác giả'));
        $grid->column('is_display', __('Trạng thái hiển thị'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Communication", "grid");
        });
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'))->sortable();
        $grid->model()->where('type', 0);
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
        $show->field('title', __('Tiêu đề'));
        $show->field('summary', __('Tóm tắt'));
        $show->field('content', __('Nội dung'))->textarea();
        $show->field('slug', __('Đường dẫn'));
        $show->field('start_date', __('Ngày bắt đầu'));
        $show->field('end_date', __('Ngày kết thúc'));
        $show->field('image', __('Hình ảnh'))->image();
        $show->field('public_date', __('Ngày công khai'));
        $show->field('author', __('Tác giả'));
        $show->field('is_display', __('Trạng thái hiển thị'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Communication", "grid");
        });
        $show->field('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
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
        $statusOptions = (new UtilsCommonHelper)->commonCode("Core", "Status", "description_vi", "value");
        $displayOptions = (new UtilsCommonHelper)->commonCode("Communication", "Status", "description_vi", "value");
        $statusDefault = $statusOptions->keys()->first();
        $displayDefault = $statusOptions->keys()->first();
        $branchs = (new UtilsCommonHelper)->optionsBranch();
        $business = (new UtilsCommonHelper)->currentBusiness();

        $form = new Form(new CommunicationModel);
        $form->hidden('type', __('Phân loại'))->value(0);
        if ($form->isEditing()) {
            $id = request()->route()->parameter('event');
//            dd(request()->route());
            $branchId = $form->model()->find($id)->getOriginal("branch_id");
            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->default($branchId);
        }
        else {
            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->required();
        }

        $form->text('title', __('Tiêu đề'));
        $form->text('summary', __('Tóm tắt'));
        $form->textarea('content', __('Nội dung'));
        $form->text('slug', __('Đường dẫn'));
        $form->date('start_date', __('Ngày bắt đầu'));
        $form->date('end_date', __('Ngày kết thúc'));
        $form->image('image', __('Hình ảnh'));
        $form->date('public_date', __('Ngày công khai'));
        $form->text('author', __('Tác giả'));
        $form->select('is_display', __('Trạng thái hiển thị'))->options($displayOptions)->default($displayDefault);
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

        return $form;
    }
}
