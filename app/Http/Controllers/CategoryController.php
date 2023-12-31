<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductGroupModel;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use App\Http\Models\Core\Branch;

class CategoryController extends Controller
{
    use ResponseFormattingTrait;
    public function find(Request $request)
    {
        $branchId = $request->get('branch_id');
        return CategoryModel::where('branch_id', $branchId)->get();
    }
    public function findByProductGroup($id,Request $request)
    {
//        $product_group_id = $request->input('product_group_id',null);
        $resultTmp= CategoryModel::where('product_group_id', $id)->get();
        $total=count($resultTmp);
        $response = $this->_formatBaseResponseWithTotal(200, $resultTmp, $total, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
    public function getById(Request $request)
    {
        $id = $request->get('q');
        return CategoryModel::find($id);
    }
    public static function findById(Request $request)
    {
        $id = $request->get('q');
        return CategoryModel::find($id);
    }
    public static function getAll()
    {
        $productGroups = CategoryModel::query()->get();
        return response()->json($productGroups);
    }
}
