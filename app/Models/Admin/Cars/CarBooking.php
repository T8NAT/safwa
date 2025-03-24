<?php

namespace App\Models\Admin\Cars;

use App\Models\User;
use App\Models\Admin\Cars\Car;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarBooking extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'id'    => 'integer',
        'car_id'            => 'integer',
        'user_id'           => 'integer',
        'slug'              => 'string',
        'car_model'         => 'string',
        'car_number'        => 'string',
        'location'          => 'string',
        'pickup_time'       => 'string',
        'pickup_date'       => 'string',
        'round_pickup_time' => 'string',
        'round_pickup_date' => 'string',
        'destination'       => 'string',
        'phone'             => 'string',
        'email'             => 'string',
        'type'              => 'string',
        'message'           => 'string',
        'status'            => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];
    public function cars(){
        return $this->belongsTo(Car::class,'car_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
