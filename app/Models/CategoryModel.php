<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    protected $table = 'category';

    public function branch()
    {
        return $this->belongsTo(BranchModel::class, 'branch_id');
    }
    public function productGroup()
    {
        return $this->belongsTo(ProductGroupModel::class, 'product_group_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
