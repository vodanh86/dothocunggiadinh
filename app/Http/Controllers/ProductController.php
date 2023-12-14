<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function find(Request $request)
    {
        $branchId = $request->get('branch_id');
        return ProductModel::where('branch_id', $branchId)->get();
    }
    public function getById(Request $request)
    {
        $id = $request->get('q');
        return ProductModel::find($id);
    }
    public static function findById(Request $request)
    {
        $id = $request->get('q');
        return ProductModel::find($id);
    }
    public static function getAll()
    {
        $productGroups = ProductModel::query()->get();
        return response()->json($productGroups);
    }
}
