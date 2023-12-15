<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductGroupModel;
use Illuminate\Http\Request;
use App\Http\Models\Core\Branch;

class CategoryController extends Controller
{
    public function find(Request $request)
    {
        $branchId = $request->get('branch_id');
        return CategoryModel::where('branch_id', $branchId)->get();
    }
    public function findByProductGroup(Request $request)
    {
        $product_group_id = $request->get('product_group_id');
        return CategoryModel::where('product_group_id', $product_group_id)->get();
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
