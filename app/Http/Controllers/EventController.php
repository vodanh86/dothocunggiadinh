<?php

namespace App\Http\Controllers;

use App\Models\CommunicationModel;
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
    public static function find($id, Request $request)
    {
        $data= CommunicationModel::find($id);
        $dataType=$data->type;
        if($dataType!==1){
            $errorResponse=self::formatResponse(400, null, 'Lấy dữ liệu thất bại.');
            return response()->json($errorResponse);
        }
        $response = self::formatResponse(200, $data, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    public static function getAll()
    {
        $productGroups = CommunicationModel::query()->get();
        return response()->json($productGroups);
    }

    public function listComingSoonEvents(Request $request)
    {
        $perPage = $request->input('limit', 3);
        $resultTmp = DB::select("select * from communication c where c.`type` = 0 and c.status =1 order by c.end_date desc  LIMIT :perPage", ['perPage' => $perPage]);

        $response = $this->_formatBaseResponse(200, $resultTmp, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    public function getBySlug(Request $request)
    {
        $slug = $request->input('slug', '');
        $news=CommunicationModel::where('slug', $slug)
            ->where('status',1)
            ->where('type',0)
            ->first();

        $response = $this->_formatBaseResponse(200, $news, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
    private static function formatResponse(int $statusCode, $data, string $message)
    {
        return [
            'statusCode' => $statusCode,
            'data' => $data,
            'message' => $message,
        ];
    }


}
