<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\ProductGroupController;
use App\Models\CategoryModel;
use App\Models\ProductGroupModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ACategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Phân loại sản phẩm';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CategoryModel());
        $grid->column('productGroup.name', __('Tên loại sản phẩm'));
        $grid->column('name', __('Tên phân loại'));
        $grid->column('description', __('Mô tả'))->textarea();
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $grid->column('created_at', __('Ngày tạo'))->sortable();
        $grid->column('updated_at', __('Ngày cập nhật'));
//        $grid->model()->where('type', 1)
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
        $show = new Show(CategoryModel::findOrFail($id));
        $show->field('productGroup.name', __('Tên loại sản phẩm'));
        $show->field('name', __('Tên phân loại'));
        $show->field('description', __('Mô tả'));
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

        $form = new Form(new CategoryModel);
        $productGroup=(new UtilsCommonHelper)->findAllProductGroup();
        if ($form->isEditing()) {
            $id = request()->route()->parameter('category');
            $productGroupId = $form->model()->find($id)->getOriginal("product_group_id");

            $form->select('product_group_id', __('Tên loại sản phẩm'))->options($productGroup)->default($productGroupId);
        }
        else {
            $form->select('product_group_id', __('Tên loại sản phẩm'))->options($productGroup)->required();
        }
        $form->text('name', __('Tên phân loại'));
        $form->text('description', __('Mô tả'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

//        $urlProductGroup = env('APP_URL') . '/api/product-group';
//        $script = <<<EOT
//        $(function() {
//            var branchSelect = $(".branch_id");
//            var productGroupSelect = $(".product_group_id");
//            var productGroupSelectDOM = document.querySelector('.product_group_id');
//            var optionsProductGroup = {};
//
//
//            branchSelect.on('change', function() {
//
//                productGroupSelect.empty();
//                optionsProductGroup = {};
//
//                var selectedBranchId = $(this).val();
//                if(!selectedBranchId) return
//                $.get("$urlProductGroup", { branch_id: selectedBranchId }, function (productGroups) {
//
//                    productGroupSelectDOM.removeAttribute('disabled');
//                    var productGroupActive = productGroups.filter(function (cls) {
//                        return cls.status === 1;
//                    });
//                    $.each(productGroupActive, function (index, cls) {
//
//                        optionsProductGroup[cls.id] = cls.name;
//                    });
//                    productGroupSelect.empty();
//                    productGroupSelect.append($('<option>', {
//                        value: '',
//                        text: ''
//                    }));
//                    $.each(optionsProductGroup, function (id, productGroupName) {
//                        productGroupSelect.append($('<option>', {
//                            value: id,
//                            text: productGroupName
//                        }));
//                    });
//                    productGroupSelect.trigger('change');
//                });
//            });
//        });
//        EOT;
//        Admin::script($script);
        return $form;
    }
}
