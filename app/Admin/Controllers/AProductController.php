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
        $grid->column('productGroup.name', __('Tên nhóm sản phẩm'));
        $grid->column('category.name', __('Loại sản phẩm'));
        $grid->column('name', __('Tên sản phẩm'))->filter('like');
        $grid->column('slug', __('Đường dẫn'));
        $grid->column('qr_code', __('Đường dẫn QR Code'))->qrcode();
        $grid->column('video', __('Video'))->display(function ($video) {
            $urlProduct = env('APP_URL').'/storage';
            return " <video width='200' height='200' controls> <source src=$urlProduct/$video type='video/mp4'> </video>";
        });
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('image2', __('Hình ảnh 2'))->image();
        $grid->column('image3', __('Hình ảnh 3'))->image();
        $grid->column('image4', __('Hình ảnh 4'))->image();
        $grid->column('image5', __('Hình ảnh 5'))->image();
        $grid->column('image6', __('Hình ảnh 6'))->image();
        $grid->column('image6', __('Hình ảnh 7'))->image();
        $grid->column('description', __('Mô tả'))->textarea();
        $grid->column('detail', __('Chi tiết sản phẩm'))->textarea();
        $grid->column('sell_policy', __('Chính sách bán hàng'))->textarea();
        $grid->column('payment_policy', __('Chính sách thanh toán'))->textarea();
        $grid->column('change_policy', __('Chính sách đổi trả'))->textarea();
        $grid->column('is_outstanding', __('Sản phẩm nổi bật'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Highlight", "grid");
        });
//        $grid->column('freeShip', __('Ưu đãi vận chuyển'))->display(function ($status) {
//            return UtilsCommonHelper::statusFormatter($status, "Product", "grid");
//        });
        $grid->column('tags', __('Tags'));
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
        $grid->fixColumns(0, -1);
        $grid->disableFilter();
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
        $show->field('productGroup.name', __('Tên nhóm sản phẩm'));
        $show->field('category.name', __('Tên phân loại'));
        $show->field('slug', __('Đường dẫn'));
        $show->field('qr_code', __('Đường dẫn QR Code'));
        $show->field('description', __('Mô tả'));
        $show->field('productGroup.name', __('Tên nhóm sản phẩm'));
        $show->field('category.name', __('Loại sản phẩm'));
        $show->field('name', __('Tên sản phẩm'));
        $show->field('video', __('Video'))->display(function ($video) {
            $urlProduct = env('APP_URL').'/storage';
            return " <video width='220' height='220' controls> <source src=$urlProduct/$video type='video/mp4'> </video>";
        });
        $show->field('image', __('Hình ảnh'))->image();
        $show->field('image2', __('Hình ảnh 2'))->image();
        $show->field('image3', __('Hình ảnh 3'))->image();
        $show->field('image4', __('Hình ảnh 4'))->image();
        $show->field('image5', __('Hình ảnh 5'))->image();
        $show->field('image6', __('Hình ảnh 6'))->image();
        $show->field('image7', __('Hình ảnh 7'))->image();
        $show->field('description', __('Mô tả'));
        $show->field('detail', __('Chi tiết sản phẩm'));
        $show->field('sell_policy', __('Chính sách bán hàng'));
        $show->field('payment_policy', __('Chính sách thanh toán'));
        $show->field('change_policy', __('Chính sách đổi trả'));
        $show->field('is_outstanding', __('Sản phẩm nổi bật'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Highlight", "grid");
        });
//        $show->field('freeShip', __('Ưu đãi vận chuyển'))->display(function ($status) {
//            return UtilsCommonHelper::statusFormatter($status, "Product", "grid");
//        });
        $show->field('tags', __('Tags'));
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
        $statusDefault = $statusOptions->keys()->first();
        $highlightOptions = (new UtilsCommonHelper)->commonCode("Highlight", "Status", "description_vi", "value");
        $highlightDefault = $highlightOptions->keys()->first();
        $freeShipOptions = (new UtilsCommonHelper)->commonCode("Product", "freeShip", "description_vi", "value");
        $freeShipDefault = $freeShipOptions->keys()->first();

        $form = new Form(new ProductModel());
        $form->hidden('slug', __('Đường dẫn'));
        $form->hidden('qr_code', __('Đường dẫn'));
        $productGroup = (new UtilsCommonHelper)->findAllProductGroup();
        if ($form->isEditing()) {
            $id = request()->route()->parameter('product');
            $productGroupId = $form->model()->find($id)->getOriginal("product_group_id");
            $category = (new UtilsCommonHelper)->optionsCategoryByProductGroupId($productGroupId);
            $categoryId = $form->model()->find($id)->getOriginal("category_id");
            $form->select('product_group_id', __('Nhóm sản phẩm'))->options($productGroup)->default($productGroupId);
            $form->select('category_id', __('Loại sản phẩm'))->options($category)->default($categoryId);
        } else {
            $form->select('product_group_id', __('Nhóm sản phẩm'))->options($productGroup)->required();
            $form->select('category_id', __('Loại sản phẩm'))->options()->required()->disable();
        }
        $form->text('name', __('Tên sản phẩm'))->required();
        $form->file('video', __('Video'));
        $form->image('image', __('Hình ảnh'));
        $form->image('image2', __('Hình ảnh 2'));
        $form->image('image3', __('Hình ảnh 3'));
        $form->image('image4', __('Hình ảnh 4'));
        $form->image('image5', __('Hình ảnh 5'));
        $form->image('image6', __('Hình ảnh 6'));
        $form->image('image7', __('Hình ảnh 7'));
        $form->ckeditor('description', __('Mô tả'));
        $form->ckeditor('detail', __('Chi tiết sản phẩm'))->required();
        $form->ckeditor('sell_policy', __('Chính sách giao hàng'));
        $form->ckeditor('payment_policy', __('Chính sách thanh toán'));
        $form->ckeditor('change_policy', __('Chính sách đổi trả'));
        $form->select('is_outstanding', __('Sản phẩm nổi bật'))->options($highlightOptions)->default($highlightDefault);
        $form->text('tags', __('Tags'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);
        $form->saving(function ($form) {
            $urlFrontEnd = env('FRONT_END_PRODUCT_URL');
            if (!($form->model()->id && $form->model()->name === $form->name)) {
                $slugConvert = UtilsCommonHelper::create_slug($form->name, ProductModel::get());
                $form->slug = $slugConvert;
                $form->qr_code = $urlFrontEnd.$slugConvert;
            }
        });
        $urlProductGroup = env('APP_URL') . '/api/product-group';
        $urlProductGroupId = env('APP_URL') . '/api/product-group/get-by-id';
        $urlCategory = env('APP_URL') . '/api/category/get-by-product-group';
        $script = <<<EOT
        $(function() {
            var productGroupSelect = $(".product_group_id");
            var productGroupSelectDOM = document.querySelector('.product_group_id');
            var categorySelect = $(".category_id");
            var categorySelectDOM = document.querySelector('.category_id');
            var optionsProductGroup = {};
            var optionsCategory = {};

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
                    console.log('categorySelect:'+categorySelect);
                    categorySelect.trigger('change');
                });
            });


        });
        EOT;
        Admin::script($script);
        return $form;
    }
}
