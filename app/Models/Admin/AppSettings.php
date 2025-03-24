<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSettings extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'id'    => 'integer',
        'version'    => 'string',
        'splash_screen_image' => 'string',
        'site_title'   => 'string',
        'url_title'    => 'string',
        'android_url'    => 'string',
        'iso_url	'    => 'string',
    ];
}
