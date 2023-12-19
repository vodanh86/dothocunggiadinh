<?php

namespace App\Http\Controllers;

use App\Models\ProductGroupModel;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;

class ProductGroupController extends Controller
{
    use ResponseFormattingTrait;

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

    public static function getAllWithLimit(Request $request)
    {
        $limit = $request->input('limit', 6);
        $productGroups = ProductGroupModel::query()
            ->limit($limit)
            ->get();
        $response =
            [
                'statusCode' => 200,
                'data' => $productGroups,
                'message' => 'Lấy dữ liệu thành công',
            ];
        return response()->json($response);
    }
}
