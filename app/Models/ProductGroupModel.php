<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGroupModel extends Model
{
    protected $table = 'product_group';
    protected $hidden = [
    ];

    protected $guarded = [];

    public function branch()
    {
        return $this->belongsTo(BranchModel::class, 'branch_id');
    }
        public function category()
        {
        return $this->hasMany(CategoryModel::class);
    }
//    public function teacher()
//    {
//        return $this->belongsTo(EduTeacher::class, 'teacher_id');
//    }
//    public function business()
//    {
//        return $this->belongsTo(BusinessModel::class, 'business_id');
//    }
}
