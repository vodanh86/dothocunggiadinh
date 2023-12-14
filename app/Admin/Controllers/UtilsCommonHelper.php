<?php

namespace App\Admin\Controllers;

use App\Models\BranchModel;
use App\Models\BusinessModel;
use App\Models\CategoryModel;
use App\Models\CommonCode;
use App\Models\ProductGroupModel;
use App\Models\ProductModel;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Str;

class UtilsCommonHelper
{
    public static function commonCode($group, $type, $description, $value)
    {
        if ($group === "Core") {
            return CommonCode::where('group', $group)
                ->where('type', $type)
                ->pluck($description, $value);
        } else {
            $commonCode = CommonCode::where('business_id', Admin::user()->business_id)
                ->where('group', $group)
                ->where('type', $type)
                ->pluck($description, $value);
            return $commonCode;
        }
    }
    public static function currentBusiness()
    {
        return BusinessModel::where('id', Admin::user()->business_id)->first();
    }
    public static function commonCodeGridFormatter($group, $type, $description, $value)
    {
        $commonCode = CommonCode::where('business_id', Admin::user()->business_id)
            ->where('group', $group)
            ->where('type', $type)
            ->where('value', $value)
            ->first();
        return $commonCode ? $commonCode->$description : '';
    }

    public static function optionsProductGroupByBranchId($branchId)
    {
        if ($branchId !== null) {
            return ProductGroupModel::where("branch_id", $branchId)->where('status', 1)->pluck('name', 'id');
        }
        return ProductGroupModel::all()->where('status', 1)->pluck('name', 'id');
    }
    public static function optionsCategoryByProductGroupId($productGroupId)
    {
        if ($productGroupId !== null) {
            return CategoryModel::where("product_group_id", $productGroupId)->where('status', 1)->pluck('name', 'id');
        }
        return CategoryModel::all()->where('status', 1)->pluck('name', 'id');
    }
    public static function optionsProductByBranchId($branchId)
    {
        if ($branchId !== null) {
            return ProductModel::where("branch_id", $branchId)->where('status', 1)->pluck('name', 'id');
        }
        return ProductModel::all()->where('status', 1)->pluck('name', 'id');
    }
    //Kiem tra ten lai(doi lai)
    public static function statusFormatter($value, $group, $isGrid)
    {
        $result = $value ? $value : 0;
        if ($group === "Core") {
            $commonCode = CommonCode::where('group', $group)
                ->where('type', 'Status')
                ->where('value', $result)
                ->first();
        } else {
            //TODO: CHECK lai
            $commonCode = CommonCode::where('business_id', Admin::user()->business_id)
                ->where('group', $group)
                ->where('type', 'Status')
                ->where('value', $result)
                ->first();
        }
        if ($commonCode && $isGrid === "grid") {
            return $result === 1 ? "<span class='label label-success'>$commonCode->description_vi</span>" : "<span class='label label-danger'>$commonCode->description_vi</span>";
        }
        return $commonCode->description_vi;
    }
    public static function percentFormatter($value, $isGrid)
    {

        if ($isGrid === "grid") {
            return $value === 0 ? "<span class='label label-infor' style='text-align: center;' >$value %</span>" : "<span class='label label-warning' style='text-align: center;' >$value %</span>";
        }
        return $value;
    }
    public static function statusFormFormatter()
    {
        return self::commonCode("Core", "Status", "description_vi", "value");
    }
    public static function statusGridFormatter($status)
    {
        return self::statusFormatter($status, "Core", "grid");
    }
    public static function statusDetailFormatter($status)
    {
        return self::statusFormatter($status, "Core", "detail");
    }
    public static function optionsBranch()
    {
        return BranchModel::where('business_id', Admin::user()->business_id)->where('status', 1)->pluck('branch_name', 'id');
    }


    public static function generateTransactionId($type)
    {
        $today = date("ymd");
        $currentTime = Carbon::now('Asia/Bangkok');
        $time = $currentTime->format('His');
        $userId = Str::padLeft(Admin::user()->id, 6, '0');
        $code = $type . $today . $userId . $time;
        return $code;
    }
}
