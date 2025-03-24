<?php

namespace App\Models\Admin\Services;

use App\Models\Admin\Services\ServiceArea;
use App\Models\Admin\Services\ServiceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AreaHasType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'id'    => 'integer',
        'service_area_id'        => 'integer',
        'service_type_id'        => 'integer',
    ];

    public function area() {
        return $this->belongsTo(ServiceArea::class,'service_area_id');
    }

    public function type() {
        return $this->belongsTo(ServiceType::class,'service_type_id');
    }
}
