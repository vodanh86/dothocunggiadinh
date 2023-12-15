<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellInformationModel extends Model
{
    protected $table = 'sell_information';

    public function branch()
    {
        return $this->belongsTo(BranchModel::class, 'branch_id');
    }
    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
