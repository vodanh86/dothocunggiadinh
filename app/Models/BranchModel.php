<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchModel extends Model
{
    protected $table = 'core_branch';

    public function business()
    {
        return $this->belongsTo(BusinessModel::class, 'business_id');
    }
//    public function classes()
//    {
//        return $this->hasMany(EduClass::class,'branch_code');
//    }
    protected $hidden = [
    ];

    protected $guarded = [];
}
