<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessModel extends Model
{
    protected $table = 'core_business';

    public function branchs()
    {
        return $this->hasMany(BranchModel::class);
    }
//    public function businessType()
//    {
//        return $this->belongsTo(Business_Type::class, 'type');
//    }
    protected $hidden = [];

    protected $guarded = [];
}




