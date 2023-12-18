<?php

namespace App\Admin\Controllers;

use App\Models\SellInformationModel;
use App\Models\SocialInformationModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ASellInformationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Thông tin bán hàng';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SellInformationModel());
        $grid->column('branch.branch_name', __('Tên chi nhánh'));
        $grid->column('product.name', __('Tên sản phẩm'));
        $grid->column('type', __('Phân loại'));
        $grid->column('origin_price', __('Giá ban đầu'));
        $grid->column('current_price', __('Giá hiện tại'));
        $grid->column('sale_percent', __('Tỉ lệ giảm (%)'))->display(function ($percent){
            return UtilsCommonHelper::percentFormatter($percent,"grid");
        });
        $grid->column('quantity', __('Số lượng'));
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
        $show = new Show(SellInformationModel::findOrFail($id));
        $show->field('branch.branch_name', __('Tên chi nhánh'));
        $show->field('product.name', __('Tên sản phẩm'));
        $show->field('type', __('Phân loại'));
        $show->field('origin_price', __('Giá ban đầu'));
        $show->field('current_price', __('Giá hiện tại'));
        $show->field('sale_percent', __('Tỉ lệ giảm (%)'));
        $show->field('quantity', __('Số lượng'));
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

        $form = new Form(new SellInformationModel);
        if ($form->isEditing()) {
            $id = request()->route()->parameter('sell_information');
            $branchId = $form->model()->find($id)->getOriginal("branch_id");
            $product = (new UtilsCommonHelper)->optionsProductByBranchId($branchId);
            $productId = $form->model()->find($id)->getOriginal("product_id");

            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->default($branchId);
            $form->select('product_id', __('Tên sản phẩm'))->options($product)->default($productId);
        } else {
            $form->select('branch_id', __('Tên chi nhánh'))->options($branchs)->required();
            $form->select('product_id', __('Tên sản phẩm'))->options()->required()->disable();
        }
        $form->text('type', __('Phân loại'))->required();
        $form->currency('origin_price', __('Giá ban đầu'))->symbol('VND')->required();
        $form->currency('current_price', __('Giá hiện tại'))->symbol('VND')->required()->readonly();
        $form->rate('sale_percent', __('Tỉ lệ giảm (%)'))->default(0)->required();
        $form->number('quantity', __('Số lượng'))->min(0)->required();
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


            var originPrice=$("#origin_price");
            var salePercent=$("#sale_percent");
            var currentPrice=$("#current_price");
            var salePercentValue=0;
            var originPriceValue;

            function parseFormattedNumber(num) {
                return parseFloat(num.replace(/,/g, ''));
            };

            originPrice.on('change', function() {
                var originPriceValue = parseFormattedNumber($(this).val());
                salePercent.on('change', function() {
                    salePercentValue = parseFormattedNumber($(this).val());
                    var valueTotal = originPriceValue * (1 - salePercentValue/100);
                    currentPrice.val(valueTotal);
                });
                currentPrice.val($(this).val() * (1 - salePercentValue/100));
            });

            salePercent.on('change', function() {
                var salePercentValue = parseFormattedNumber($(this).val());
                console.log('salePercentValue: '+salePercentValue)
                originPriceValue=parseFormattedNumber(originPrice.val());
                originPrice.on('change', function() {
                    originPriceValue = parseFormattedNumber($(this).val());
                    var valueTotal = originPriceValue * (1 - salePercentValue/100);
                    currentPrice.val(valueTotal);
                });


                currentPrice.val(originPriceValue * (1 - salePercentValue/100));
            });

        });
        EOT;
        Admin::script($script);
        return $form;
    }
}
