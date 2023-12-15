<?php

namespace App\Http\Controllers;

use App\Models\BranchModel;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function find(Request $request)
    {
        $businessId = $request->get('business_id');
        return BranchModel::where('business_id', $businessId)->get();
    }
    public function getById(Request $request)
    {
        $id = $request->get('q');
        return BranchModel::find($id);
    }
}
