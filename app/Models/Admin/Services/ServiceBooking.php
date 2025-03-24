<?php

namespace App\Models\Admin\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceBooking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'id'                => 'integer',
        'service_id'        => 'integer',
        'user_id'           => 'integer',
        'slug'              => 'string',
        'number_bottles'    => 'string',
        'name'              => 'string',
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
    public function service(){
        return $this->belongsTo(Service::class,'service_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
