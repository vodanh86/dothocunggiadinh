<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonCodeModel extends Model
{
    protected $table = 'core_system_information';
    public function business()
    {
        return $this->belongsTo(BusinessModel::class, 'business_id');
    }
    protected $hidden = [
    ];

    protected $guarded = [];
}
