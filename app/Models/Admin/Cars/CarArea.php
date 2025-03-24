<?php

namespace App\Models\Admin\Cars;

use App\Models\Admin\Admin;
use App\Models\Admin\Cars\AreaHasType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarArea extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts  = [
        'id'    => 'integer',
        'slug'                      => 'string',
        'name'                      => 'string',
        'status'                    => 'integer',
        'last_edit_by'              => 'integer',
    ];
    public function admin() {
        return $this->belongsTo(Admin::class,'last_edit_by','id');
    }

    public function types() {
        return $this->hasMany(AreaHasType::class,'car_area_id');
    }
}
