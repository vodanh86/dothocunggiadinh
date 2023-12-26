<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\DeliverySystemModel;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliverySystemController extends Controller
{
    use ResponseFormattingTrait;
    public function find(Request $request)
    {
        $branchId = $request->get('branch_id');
        return DeliverySystemModel::where('branch_id', $branchId)->get();
    }
    public function getById(Request $request)
    {
        $id = $request->get('q');
        return DeliverySystemModel::find($id);
    }
    public static function getAll()
    {
        $productGroups = DeliverySystemModel::query()->get();
        return response()->json($productGroups);
    }

    public function getAllDeliverySystem(Request $request)
    {
        $resultTmp = DB::select("select ds.id, ds.name, ds.phone_number, ds.email, ds.address, ds.`address_map`, ds.note, ds.`order`,ds.status, ds.created_at, ds.updated_at from delivery_system ds where ds.status =1 order by ds.`order` asc;");

        $response = $this->_formatBaseResponse(200, $resultTmp, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
