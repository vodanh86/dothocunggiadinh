<?php

namespace App\Http\Controllers;

use App\Models\CommunicationModel;

class CommunicationController extends Controller
{
    public function getAll()
    {
        $branches = CommunicationModel::get();
        return $branches;
    }
    public function getById($id)
    {
        $branches = CommunicationModel::where('id',$id)->get();
        return $branches;
    }
}
