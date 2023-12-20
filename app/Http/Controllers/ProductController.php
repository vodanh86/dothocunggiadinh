<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\s;

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

    public function getByProductGroup($id, Request $request)
    {
//        $productGroupId = $request->input('productGroup');
//        if($id===null){
//            dd($id);
//        }
        $perPage = $request->input('limit', 10);
        if ($id === null) {
            $error = $this->_formatBaseResponse(400, null, 'Yêu cầu nhập product group Id');
            return response()->json($error);
        } else {
            $resultTmp = DB::select("select
	p.id as id,
	p.name as productName,
	p.image as image,
	p.freeShip,
	si.origin_price ,
	si.current_price ,
	si.sale_percent
from
	product p
inner join sell_information si on
	p.id = si.product_id
	inner join product_group pg on pg.id=p.product_group_id
where
	pg.id = :productGroupId and pg.status =1
order by
	p.updated_at desc  LIMIT :perPage", ['productGroupId' => $id,
                'perPage' => $perPage]);
            $total = count($resultTmp);
            $response = $this->_formatBaseResponseWithTotal(200, $resultTmp, $total, 'Lấy dữ liệu thành công');
            return response()->json($response);
        }
    }

    public function relatedProduct($id, Request $request)
    {

        $perPage = $request->input('limit', 5);
        if ($id === null) {
            $error = $this->_formatBaseResponse(400, null, 'Yêu cầu nhập product group Id');
            return response()->json($error);
        } else {
            $product = ProductModel::find($id);
            $categoryId = $product->category_id;

            $resultTmp = DB::select("select
	p.id as id,
	p.name as productName,
	p.image as image,
	p.freeShip,
	si.origin_price ,
	si.current_price ,
	si.sale_percent,
	c.name
from
	product p
inner join sell_information si on
	p.id = si.product_id
	inner join category c  on c.id=p.category_id
where
	c.id= :categoryId and p.id != :productId
order by
	p.updated_at desc  LIMIT :perPage ", [
                'categoryId' => $categoryId,
                'productId' => $id,
                'perPage' => $perPage,
            ]);

            $uniqueProductNames = [];
            $filteredResults = array_filter($resultTmp, function ($result) use (&$uniqueProductNames) {
                if (!isset($uniqueProductNames[$result->productName])) {
                    $uniqueProductNames[$result->productName] = true;
                    return true;
                }
                return false;
            });
            $total = count($filteredResults);

            $response = $this->_formatBaseResponseWithTotal(200, $filteredResults, $total, 'Lấy dữ liệu thành công');
            return response()->json($response);
        }
    }

    //tim kiem san pham
    public function searchProduct(Request $request)
    {
        $perPage = $request->input('limit', 5);
        $productName = $request->input('name', '');

        $resultTmp = DB::select("SELECT p.id as id, p.name as productName, p.image as image, p.freeShip,
           si.origin_price, si.current_price, si.sale_percent
    FROM product p
    INNER JOIN sell_information si ON p.id = si.product_id
    WHERE p.name like ?
    ORDER BY p.updated_at DESC
    LIMIT ?", ['%' . $productName . '%', $perPage]);


        $total = count($resultTmp);

        $response = $this->_formatBaseResponseWithTotal(200, $resultTmp, $total, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    //tim kiem san pham theo category
    public function filterByCategory(Request $request)
    {
        $perPage = $request->input('limit', 10);
        $filters = $request->input('category', []);

//        $values = [];
//        foreach ($filters as $filter) {
//            $values[] = "'" . $filter['value'] . "'";
//        }
//
//        $field = '(' . implode(',', $values) . ')';

        $products = DB::table('product as p')
            ->select('p.id as id', 'p.name as productName', 'p.image as image', 'p.freeShip', 'si.origin_price', 'si.current_price', 'si.sale_percent')
            ->join('sell_information as si', 'p.id', '=', 'si.product_id')
            ->join('category as ca', 'p.category_id', '=', 'ca.id')
            ->whereIn('ca.name', array_column($filters, 'value'))
            ->orderBy('p.updated_at', 'DESC')
            ->take($perPage)
            ->get();

        $total = $products->count();


        $response = $this->_formatBaseResponseWithTotal(200, $products, $total, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    //chi tiet san pham
    public function detailProduct($id, Request $request)
    {
        $products = DB::table('product as p')
            ->select('p.id as id',
                'p.name as productName',
                'p.image as image',
                'p.video as video',
                'p.sell_policy',
                'p.payment_policy',
                'p.change_policy',
                'p.description',
                'p.detail',
                'p.freeShip')
            ->where('p.id', $id)
            ->where('p.status', 1)
            ->get();

        $socialInformation = DB::table('social_information as si')
            ->select('si.id',
                'si.platform',
                'si.link',
                'si.image')
            ->join('product as p', 'p.id', '=', 'si.product_id')
            ->where('p.id', $id)
            ->where('p.status', 1)
            ->where('si.status', 1)
            ->get();

        $sellInformation = DB::table('sell_information as si')
            ->select('si.id',
                'si.type',
                'si.origin_price',
                'si.current_price',
                'si.sale_percent',
                'si.quantity')
            ->join('product as p', 'p.id', '=', 'si.product_id')
            ->where('p.id', $id)
            ->where('p.status', 1)
            ->where('si.status', 1)
            ->orderBy('si.current_price', 'ASC')
            ->get();

        $productDetail = [
            'product' => $products,
            'socialInformation' => $socialInformation,
            'sellInformation' => $sellInformation,
        ];
        $response = $this->_formatBaseResponse(200, $productDetail, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

    public function getBySlug(Request $request)
    {
        $slug = $request->input('slug', '');

        $productBySlug = ProductModel::where('slug', $slug)
            ->where('status',1)
            ->first();

        $id = $productBySlug->id;

        $products = DB::table('product as p')
            ->select('p.id as id',
                'p.name as productName',
                'p.image as image',
                'p.video as video',
                'p.sell_policy',
                'p.payment_policy',
                'p.change_policy',
                'p.description',
                'p.detail',
                'p.freeShip')
            ->where('p.id', $id)
            ->where('p.status', 1)
            ->get();

        $socialInformation = DB::table('social_information as si')
            ->select('si.id',
                'si.platform',
                'si.link',
                'si.image')
            ->join('product as p', 'p.id', '=', 'si.product_id')
            ->where('p.id', $id)
            ->where('p.status', 1)
            ->where('si.status', 1)
            ->get();

        $sellInformation = DB::table('sell_information as si')
            ->select('si.id',
                'si.type',
                'si.origin_price',
                'si.current_price',
                'si.sale_percent',
                'si.quantity')
            ->join('product as p', 'p.id', '=', 'si.product_id')
            ->where('p.id', $id)
            ->where('p.status', 1)
            ->where('si.status', 1)
            ->orderBy('si.current_price', 'ASC')
            ->get();

        $productDetail = [
            'product' => $products,
            'socialInformation' => $socialInformation,
            'sellInformation' => $sellInformation,
        ];
        $response = $this->_formatBaseResponse(200, $productDetail, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }

}
