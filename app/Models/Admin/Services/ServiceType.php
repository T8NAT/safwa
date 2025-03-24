<?php

namespace App\Models\Admin\Services;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts  = [
        'id'    => 'integer',
        'slug'   => 'string',
        'name'   => 'string',
        'status' => 'integer',
        'last_edit_by' => 'integer'
    ];

    public function admin() {
        return $this->belongsTo(Admin::class,'last_edit_by','id');
    }

    public function area() {
        return $this->belongsTo(ServiceArea::class,'service_area_id');
    }
}
