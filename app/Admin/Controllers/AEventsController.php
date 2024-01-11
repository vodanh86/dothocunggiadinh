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
        $grid->column('title', __('Tiêu đề'))->filter('like');
        $grid->column('summary', __('Tóm tắt'))->textarea();
        $grid->column('content', __('Nội dung'))->textarea();
        $grid->column('slug', __('Đường dẫn'));
        $grid->column('start_date', __('Ngày bắt đầu'))->filter('range', 'date');
        $grid->column('end_date', __('Ngày kết thúc'))->filter('range', 'date');
        $grid->column('image', __('Hình ảnh'))->image();
//        $grid->column('public_date', __('Ngày công khai'));
        $grid->column('author', __('Tác giả'))->filter('like');
//        $grid->column('is_display', __('Trạng thái hiển thị'))->display(function ($status) {
//            return UtilsCommonHelper::statusFormatter($status, "Communication", "grid");
//        });
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $grid->column('created_at', __('Ngày tạo'));
        $grid->column('updated_at', __('Ngày cập nhật'))->sortable();
        $grid->model()->where('type', 0);
        $grid->model()->orderBy('created_at', 'desc');
        $grid->fixColumns(0, -1);
        $grid->disableFilter();
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
        $show->field('content', __('Nội dung'));
        $show->field('slug', __('Đường dẫn'));
        $show->field('start_date', __('Ngày bắt đầu'));
        $show->field('end_date', __('Ngày kết thúc'));
        $show->field('image', __('Hình ảnh'))->image();
//        $show->field('public_date', __('Ngày công khai'));
        $show->field('author', __('Tác giả'));
//        $show->field('is_display', __('Trạng thái hiển thị'))->display(function ($status) {
//            return UtilsCommonHelper::statusFormatter($status, "Communication", "grid");
//        });
        $show->field('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $show->field('created_at', __('Ngày tạo'));
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
        $displayOptions = (new UtilsCommonHelper)->commonCode("Communication", "Status", "description_vi", "value");
        $statusDefault = $statusOptions->keys()->first();
        $displayDefault = $statusOptions->keys()->first();

        $form = new Form(new CommunicationModel);
        $form->hidden('type', __('Phân loại'))->value(0);
        $form->hidden('slug', __('Đường dẫn'));
        $form->hidden('is_display', __('Trạng thái hiển thị'))->value($displayDefault);
        if ($form->isEditing()) {
            $id = request()->route()->parameter('event');
            $branchId = $form->model()->find($id)->getOriginal("branch_id");
        }
        $form->text('title', __('Tiêu đề'));
        $form->text('summary', __('Tóm tắt'));
        $form->textarea('content', __('Nội dung'));
        $form->date('start_date', __('Ngày bắt đầu'));
        $form->date('end_date', __('Ngày kết thúc'));
        $form->image('image', __('Hình ảnh'));
//        $form->date('public_date', __('Ngày công khai'));
        $form->text('author', __('Tác giả'));
//        $form->select('is_display', __('Trạng thái hiển thị'))->options($displayOptions)->default($displayDefault);
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);
        $form->saving(function ($form) {
            if (!($form->model()->id && $form->model()->title === $form->title)) {
                $form->slug = UtilsCommonHelper::create_slug($form->title, CommunicationModel::get());
            }
        });
        return $form;
    }
}
