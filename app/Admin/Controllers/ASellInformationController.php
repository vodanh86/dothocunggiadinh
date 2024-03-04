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
    protected $title = 'Sản phẩm chi tiết';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SellInformationModel());
        $grid->column('product.name', __('Tên sản phẩm'))->filter('like');
        $grid->column('type', __('Phân loại'));
        $grid->column('image', __('Hình ảnh'))->image();
        $grid->column('origin_price', __('Giá ban đầu'));
        $grid->column('current_price', __('Giá hiện tại'));
        $grid->column('sale_percent', __('Tỉ lệ giảm (%)'))->display(function ($percent) {
            return UtilsCommonHelper::percentFormatter($percent, "grid");
        });
        $grid->column('quantity', __('Số lượng'));
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
//        $grid->disableFilter();
        $grid-> filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $statusOptions = UtilsCommonHelper::commonCode("Core", "Status", "description_vi", "value");
            $productGroupOptions = UtilsCommonHelper::findAllProduct();
            $filter->equal('product_id', 'Tên  sản phẩm')->select($productGroupOptions);

            $filter->like('type', 'Phân loại');
            $filter->equal('status', 'Trạng thái')->select($statusOptions);
            $filter->date('created_at', 'Ngày tạo');
            $filter->date('updated_at', 'Ngày cập nhật');
        });
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
        $show->field('product.name', __('Tên sản phẩm'));
        $show->field('type', __('Phân loại'));
        $show->field('image', __('Hình ảnh'))->image();
        $show->field('origin_price', __('Giá ban đầu'));
        $show->field('current_price', __('Giá hiện tại'));
        $show->field('sale_percent', __('Tỉ lệ giảm (%)'));
        $show->field('quantity', __('Số lượng'));
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
        $product = (new UtilsCommonHelper)->findAllProduct();

        $form = new Form(new SellInformationModel);
        if ($form->isEditing()) {
            $id = request()->route()->parameter('sell_information');
            $productId = $form->model()->find($id)->getOriginal("product_id");
            $form->select('product_id', __('Tên sản phẩm'))->options($product)->default($productId);
        } else {
            $form->select('product_id', __('Tên sản phẩm'))->options($product)->required();
        }
        $form->text('type', __('Phân loại'))->required();
        $form->image('image', __('Hình ảnh'))->required();
        $form->currency('origin_price', __('Giá ban đầu'))->symbol('VND')->required();
        $form->currency('current_price', __('Giá hiện tại'))->symbol('VND')->required()->readonly();
        $form->rate('sale_percent', __('Tỉ lệ giảm (%)'))->default(0)->required();
        $form->number('quantity', __('Số lượng'))->min(0)->required();
        $form->select('status', __('Trạng thái'))->options($statusOptions)->default($statusDefault);

//        $urlProduct = env('APP_URL') . '/api/product';
        $script = <<<EOT
        $(function() {
            var originPrice = $("#origin_price");
            var salePercent = $("#sale_percent");
            var currentPrice = $("#current_price");
            var salePercentValue = 0;
            var originPriceValue;

            function parseFormattedNumber(num) {
                return parseFloat(num.replace(/,/g, ''));
            };

            originPrice.on('change', function() {
                originPriceValue = parseFormattedNumber($(this).val());
                updateCurrentPrice();
            });

            salePercent.on('change', function() {
                salePercentValue = parseFormattedNumber($(this).val());
                updateCurrentPrice();
            });

            function updateCurrentPrice() {
                originPriceValue=parseFormattedNumber(originPrice.val());
                salePercentValue = parseFormattedNumber(salePercent.val());
                var valueTotal = originPriceValue * (1 - salePercentValue / 100);
                currentPrice.val(valueTotal);
            }

        });

        EOT;
        Admin::script($script);
        return $form;
    }
}
