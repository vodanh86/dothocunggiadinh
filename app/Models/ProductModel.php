<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'product';

    public function branch()
    {
        return $this->belongsTo(BranchModel::class, 'branch_id');
    }
    public function productGroup()
    {
        return $this->belongsTo(ProductGroupModel::class, 'product_group_id');
    }
    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
