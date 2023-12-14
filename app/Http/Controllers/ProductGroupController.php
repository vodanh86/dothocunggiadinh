<?php

namespace App\Http\Controllers;

use App\Models\ProductGroupModel;
use Illuminate\Http\Request;
use App\Http\Models\Core\Branch;

class ProductGroupController extends Controller
{
    public function find(Request $request)
    {
        $branchId = $request->get('branch_id');
        return ProductGroupModel::where('branch_id', $branchId)->get();
    }
    public function getById(Request $request)
    {
        $id = $request->get('q');
        return ProductGroupModel::find($id);
    }
    public static function findById(Request $request)
    {
        $id = $request->get('q');
        return ProductGroupModel::find($id);
    }
    public static function getAll()
    {
        $productGroups = ProductGroupModel::query()->get();
        return response()->json($productGroups);
    }
}
