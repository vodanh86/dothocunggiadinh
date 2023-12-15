<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactModel extends Model
{
    protected $table = 'contact';
    protected $hidden = [
    ];

    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(BranchModel::class, 'branch_id');
    }
//    public function business()
//    {
//        return $this->belongsTo(BusinessModel::class, 'business_id');
//    }
}
