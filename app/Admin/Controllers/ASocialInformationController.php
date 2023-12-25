<?php

namespace App\Admin\Controllers;

use App\Models\SocialInformationModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
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
        $grid->column('branch.branch_name', __('Tên chi nhánh'));
        $grid->column('product.name', __('Tên sản phẩm'));
        $grid->column('platform', __('Nền tảng'));
        $grid->column('link', __('Link sản phẩm'));
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('status', __('Trạng thái'))->display(function ($status) {
            return UtilsCommonHelper::statusFormatter($status, "Core", "grid");
        });
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'));
        $grid->fixColumns(0, 0);
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
        $show = new Show(SocialInformationModel::findOrFail($id));
        $show->field('branch.branch_name', __('Tên chi nhánh'));
        $show->field('product.name', __('Tên sản phẩm'));
        $show->field('platform', __('Nền tảng'));
        $show->field('link', __('Link sản phẩm'));
        $show->field('image', __('Hình ảnh'));
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

        $form = new Form(new SocialInformationModel);
        if ($form->isEditing()) {
            $id = request()->route()->parameter('social_information');
            $branchId = $form->model()->find($id)->getOriginal("branch_id");
            $product=(new UtilsCommonHelper)->optionsProductByBranchId($branchId);
            $productId = $form->model()->find($id)->getOriginal("product_id");

            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->default($branchId);
            $form->select('product_id', __('Tên sản phẩm'))->options($product)->default($productId);
        }
        else {
            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->required();
            $form->select('product_id', __('Tên sản phẩm'))->options()->required()->disable();
        }
        $form->text('platform', __('Nền tảng'));
        $form->text('link', __('Link sản phẩm'));
        $form->image('image', __('Hình ảnh'));
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

        $urlProduct = env('APP_URL') . '/api/product';
        $script = <<<EOT
        $(function() {
            var branchSelect = $(".branch_id");
            var productSelect = $(".product_id");
            var productSelectDOM = document.querySelector('.product_id');
            var optionsProduct = {};


            branchSelect.on('change', function() {

                productSelect.empty();
                optionsProduct = {};

                var selectedBranchId = $(this).val();
                if(!selectedBranchId) return
                $.get("$urlProduct", { branch_id: selectedBranchId }, function (products) {

                    productSelectDOM.removeAttribute('disabled');
                    var productActive = products.filter(function (cls) {
                        return cls.status === 1;
                    });
                    $.each(productActive, function (index, cls) {

                        optionsProduct[cls.id] = cls.name;
                    });
                    productSelect.empty();
                    productSelect.append($('<option>', {
                        value: '',
                        text: ''
                    }));
                    $.each(optionsProduct, function (id, productName) {
                        productSelect.append($('<option>', {
                            value: id,
                            text: productName
                        }));
                    });
                    productSelect.trigger('change');
                });
            });
        });
        EOT;
        Admin::script($script);
        return $form;
    }
}
