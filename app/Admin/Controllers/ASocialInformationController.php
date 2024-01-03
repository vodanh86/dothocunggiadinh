<?php

namespace App\Admin\Controllers;

use App\Models\SocialInformationModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ASocialInformationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Liên kết thương mại điện tử';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SocialInformationModel());
        $grid->column('product.name', __('Tên sản phẩm'));
        $grid->column('platform', __('Nền tảng'));
        $grid->column('link', __('Link sản phẩm'))->textarea();
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $grid->column('created_at', __('Ngày tạo'))->sortable();
        $grid->column('updated_at', __('Ngày cập nhật'));
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
        $show = new Show(SocialInformationModel::findOrFail($id));
        $show->field('product.name', __('Tên sản phẩm'));
        $show->field('platform', __('Nền tảng'));
        $show->field('link', __('Link sản phẩm'));
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
        $statusDefault = $statusOptions->keys()->first();
        $product=(new UtilsCommonHelper)->findAllProduct();

        $form = new Form(new SocialInformationModel);
        if ($form->isEditing()) {
            $id = request()->route()->parameter('social_information');
            $productId = $form->model()->find($id)->getOriginal("product_id");
            $form->select('product_id', __('Tên sản phẩm'))->options($product)->default($productId);
        }
        else {
            $form->select('product_id', __('Tên sản phẩm'))->options($product)->required();
        }
        $form->select('platform', __('Nền tảng'))->options(['Shopee' => 'Shopee', 'Lazada' => 'Lazada', 'Tiktok' => 'Tiktok']);
        $form->text('link', __('Link sản phẩm'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);
        return $form;
    }
}
