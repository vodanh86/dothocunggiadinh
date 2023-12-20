<?php

namespace App\Admin\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Quản lý sản phẩm';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductModel());
        $grid->column('branch.branch_name', __('Tên chi nhánh'));
        $grid->column('productGroup.name', __('Tên nhóm sản phẩm'));
        $grid->column('category.name', __('Loại sản phẩm'));
        $grid->column('name', __('Tên sản phẩm'));
//        $grid->column('video', __('Video'));
        $grid->column('video', __('Video'))->display(function ($video) {
            $urlProductGroup = env('APP_URL');
            return " <video width='220' height='220' controls> <source src=$urlProductGroup/$video type='video/mp4'> </video>";
        });
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('description', __('Mô tả'));
        $grid->column('detail', __('Chi tiết sản phẩm'));
        $grid->column('sell_policy', __('Chính sách bán hàng'));
        $grid->column('payment_policy', __('Chính sách thanh toán'));
        $grid->column('change_policy', __('Chính sách đổi trả'));
        $grid->column('is_outstanding', __('Sản phẩm nổi bật'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Highlight", "grid");
        });
        $grid->column('freeShip', __('Ưu đãi vận chuyển'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Product", "grid");
        });
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
        $show = new Show(ProductModel::findOrFail($id));
        $show->field('branch.branch_name', __('Tên chi nhánh'));
        $show->field('productGroup.name', __('Tên nhóm sản phẩm'));
        $show->field('name', __('Tên phân loại'));
        $show->field('description', __('Mô tả'));
        $show->field('branch.branch_name', __('Tên chi nhánh'));
        $show->field('productGroup.name', __('Tên nhóm sản phẩm'));
        $show->field('category.name', __('Loại sản phẩm'));
        $show->field('name', __('Tên sản phẩm'));
//        $show->field('video', __('Video'));
        $show->field('video', __('Video'))->display(function ($video) {
            return "<video width='220' height='220' controls> <source src=$video type='video/mp4'> </video>";
        });

        $show->field('image', __('Hình ảnh'))->image();
        $show->field('description', __('Mô tả'));
        $show->field('detail', __('Chi tiết sản phẩm'));
        $show->field('sell_policy', __('Chính sách bán hàng'));
        $show->field('payment_policy', __('Chính sách thanh toán'));
        $show->field('change_policy', __('Chính sách đổi trả'));
        $show->field('is_outstanding', __('Sản phẩm nổi bật'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Highlight", "grid");
        });
        $show->field('freeShip', __('Ưu đãi vận chuyển'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Product", "grid");
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
        $statusDefault = $statusOptions->keys()->first();
        $highlightOptions = (new UtilsCommonHelper)->commonCode("Highlight", "Status", "description_vi", "value");
        $highlightDefault = $highlightOptions->keys()->first();
        $freeShipOptions = (new UtilsCommonHelper)->commonCode("Product", "freeShip", "description_vi", "value");
        $freeShipDefault = $freeShipOptions->keys()->first();
        $branchs = (new UtilsCommonHelper)->optionsBranch();
        $business = (new UtilsCommonHelper)->currentBusiness();

        $form = new Form(new ProductModel());
        if ($form->isEditing()) {
            $id = request()->route()->parameter('product');
            $branchId = $form->model()->find($id)->getOriginal("branch_id");
            $productGroup = (new UtilsCommonHelper)->optionsProductGroupByBranchId($branchId);
            $productGroupId = $form->model()->find($id)->getOriginal("product_group_id");
            $category = (new UtilsCommonHelper)->optionsCategoryByProductGroupId($productGroupId);
            $categoryId = $form->model()->find($id)->getOriginal("category_id");

            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->default($branchId);
            $form->select('product_group_id', __('Nhóm sản phẩm'))->options($productGroup)->default($productGroupId);
            $form->select('category_id', __('Loại sản phẩm'))->options($category)->default($categoryId);
        } else {
            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->required();
            $form->select('product_group_id', __('Nhóm sản phẩm'))->options()->required()->disable();
            $form->select('category_id', __('Loại sản phẩm'))->options()->required()->disable();
        }
        $form->text('name', __('Tên sản phẩm'))->required();
        $form->file('video', __('Video'));
        $form->image('image', __('Hình ảnh'));
        $form->textarea('description', __('Mô tả'));
        $form->textarea('detail', __('Chi tiết sản phẩm'));
        $form->textarea('sell_policy', __('Chính sách bán hàng'));
        $form->textarea('payment_policy', __('Chính sách thanh toán'));
        $form->textarea('change_policy', __('Chính sách đổi trả'));
        $form->select('is_outstanding', __('Sản phẩm nổi bật'))->options($highlightOptions)->default($highlightDefault);
        $form->select('freeShip', __('Ưu đãi vận chuyển'))->options($freeShipOptions)->default($freeShipDefault);
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

        $urlProductGroup = env('APP_URL') . '/api/product-group';
        $urlProductGroupId = env('APP_URL') . '/api/product-group/get-by-id';
        $urlCategory = env('APP_URL') . '/api/category/get-by-product-group';
        $script = <<<EOT
        $(function() {
            var branchSelect = $(".branch_id");
            var productGroupSelect = $(".product_group_id");
            var productGroupSelectDOM = document.querySelector('.product_group_id');
            var categorySelect = $(".category_id");
            var categorySelectDOM = document.querySelector('.category_id');
            var optionsProductGroup = {};
            var optionsCategory = {};


            branchSelect.on('change', function() {

                productGroupSelect.empty();
                optionsProductGroup = {};

                var selectedBranchId = $(this).val();
                if(!selectedBranchId) return
                $.get("$urlProductGroup", { branch_id: selectedBranchId }, function (productGroups) {

                    productGroupSelectDOM.removeAttribute('disabled');
                    var productGroupActive = productGroups.filter(function (cls) {
                        return cls.status === 1;
                    });
                    $.each(productGroupActive, function (index, cls) {

                        optionsProductGroup[cls.id] = cls.name;
                    });
                    productGroupSelect.empty();
                    productGroupSelect.append($('<option>', {
                        value: '',
                        text: ''
                    }));
                    $.each(optionsProductGroup, function (id, productGroupName) {
                        productGroupSelect.append($('<option>', {
                            value: id,
                            text: productGroupName
                        }));
                    });
                    productGroupSelect.trigger('change');
                });
            });
            productGroupSelect.on('change', function() {
                categorySelect.empty();
                optionsCategory = {};

                var selectedProductGroupId = $(this).val();
                if(!selectedProductGroupId) return
                console.log('selectedProductGroup:' +selectedProductGroupId)
                $.get("$urlCategory", { product_group_id: selectedProductGroupId }, function (categorys) {

                    categorySelectDOM.removeAttribute('disabled');
                    var categoryActive = categorys.filter(function (cls) {
                        return cls.status === 1;
                    });
                    $.each(categoryActive, function (index, cls) {

                        optionsCategory[cls.id] = cls.name;
                    });
                    categorySelect.empty();
                    categorySelect.append($('<option>', {
                        value: '',
                        text: ''
                    }));
                    $.each(optionsCategory, function (id, categoryName) {
                        categorySelect.append($('<option>', {
                            value: id,
                            text: categoryName
                        }));
                    });
                    categorySelect.trigger('change');
                });
            });


        });
        EOT;
        Admin::script($script);
        return $form;
    }
}
