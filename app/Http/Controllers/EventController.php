<?php

namespace App\Http\Controllers;

use App\Models\CommunicationModel;
use App\Models\ProductModel;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    use ResponseFormattingTrait;


    public static function findById(Request $request)
    {
        $id = $request->get('q');
        return CommunicationModel::find($id);
    }

    public static function getAll()
    {
        $productGroups = CommunicationModel::query()->get();
        return response()->json($productGroups);
    }

    public function listComingSoonEvents(Request $request)
    {
        $perPage = $request->input('limit', 3);
//        dd($perPage);
        $resultTmp = DB::select("select * from communication c where c.`type` = 0 and c.status =1 order by c.end_date desc  LIMIT :perPage
", ['perPage' => $perPage]);

//        $result = DB::select("select p.id, p.name, pg.name as pgName, c.name as categoryName from product p  inner join product_group pg on p.product_group_id =pg.id inner join category c on pg.id=c.product_group_id");
//        return response()->json($result);
        $response = $this->_formatBaseResponse(200, $resultTmp, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    public function listHighLight()
    {
        $result = DB::select("select p.id, p.name, pg.name as pgΩName, c.name as categoryName from product p  inner join product_group pg on p.product_group_id =pg.id inner join category c on pg.id=c.product_group_id");
        return response()->json($result);
    }
}
