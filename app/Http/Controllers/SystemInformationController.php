<?php

namespace App\Http\Controllers;

use App\Models\CommunicationModel;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemInformationController extends Controller
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

    public function aboutUs()
    {
        $result = DB::select("select csi.id, csi.`group` ,csi.`type` ,csi.value ,csi.image,csi.description_vi,csi.description_en ,csi.`order`  from core_system_information csi WHERE csi.`group` ='Website' and csi.`type` ='AboutUs' and csi.status =1 order by csi.`order` asc;");
        $response = $this->_formatBaseResponse(200, $result, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
    public function contactInformation()
    {
        $result = DB::select("select csi.id, csi.`group` ,csi.`type` ,csi.value ,csi.image,csi.description_vi,csi.description_en ,csi.`order`  from core_system_information csi WHERE csi.`group` ='Website' and csi.`type` ='Contact' and csi.status =1 order by csi.value asc;");
        $response = $this->_formatBaseResponse(200, $result, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
    public function slogan()
    {
        $result = DB::select("select csi.id, csi.`group` ,csi.`type` ,csi.value ,csi.image,csi.description_vi,csi.description_en ,csi.`order`  from core_system_information csi WHERE csi.`group` ='Website' and csi.`type` ='Slogan' and csi.status =1 order by csi.value asc;");
        $response = $this->_formatBaseResponse(200, $result, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
    public function history()
    {
        $result = DB::select("select csi.id, csi.`group` ,csi.`type` ,csi.value ,csi.image,csi.description_vi,csi.description_en ,csi.`order`  from core_system_information csi WHERE csi.`group` ='Website' and csi.`type` ='History' and csi.status =1 order by csi.value asc;");
        $response = $this->_formatBaseResponse(200, $result, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
