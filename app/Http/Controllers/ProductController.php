<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function getProductDetail()
    {
//        $result = DB::select("SELECT product.id, product.name ,category.name as categoryName, product_group.name as productGroupName");

        $result = DB::select("select p.id, p.name, pg.name as pgName, c.name as categoryName from product p  inner join product_group pg on p.product_group_id =pg.id inner join category c on pg.id=c.product_group_id");
        return response()->json($result);
    }
}
