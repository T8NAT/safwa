<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementCategory extends Model
{
    use HasFactory;
    protected $table = "announcement_categories";
    protected $guarded = ['id'];
    protected $appends = [
        'editData',
    ];
    public function getEditDataAttribute() {

        $data = [
            'id'      => $this->id,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'status'      => $this->status,
        ];

        return json_encode($data);
    }
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', false);
    }

    public function scopeSearch($query,$text) {
        $query->Where("name","like","%".$text."%");
    }
}
