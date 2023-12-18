<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ResponseFormattingTrait;

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

    public function listHighLight(Request $request)
    {
        $perPage = $request->input('limit', 5);
//        dd($perPage);
        $resultTmp = DB::select("
    SELECT p.id as id, p.name as productName, p.image as image, p.freeShip,
           si.origin_price, si.current_price, si.sale_percent
    FROM product p
    INNER JOIN sell_information si ON p.id = si.product_id
    WHERE p.is_outstanding = 1
    ORDER BY p.updated_at DESC
    LIMIT :perPage", ['perPage' => $perPage]);
        $response = $this->_formatBaseResponse(200, $resultTmp, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
