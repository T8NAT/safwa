<?php

namespace App\Models\Admin\Services;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'id'    => 'integer',
        'name'  => 'string',
        'service_area_id' => 'integer',
        'service_type_id' => 'integer',
        'slug'        => 'string',
        'image'       => 'string',
        'number_bottles'   => 'string',
        'size'  => 'string',
        'price'        => 'decimal:8',
        'status'      => 'integer',
    ];

    public function type(){
        return $this->belongsTo(ServiceType::class,'service_type_id');
    }
    public function branch(){
        return $this->belongsTo(ServiceArea::class,'service_area_id');
    }
    public function bookings(){
        return $this->hasMany(ServiceBooking::class,'service_id');
    }
}
