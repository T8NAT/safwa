<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicSettings extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'id'    => 'integer',
        'mail_config'               => 'object',
        'push_notification_config'  => 'object',
        'broadcast_config'          => 'object',
        'status'    => 'integer',
        'site_name' => 'string',
        'site_title'   => 'string',
        'base_color'    => 'string',
        'secondary_color'    => 'string',
        'country_code'    => 'string',
        'google_api_key'    => 'string',
        'web_version'    => 'string',
        'location'    => 'string',
        'timezone'    => 'string',
        'site_logo'    => 'string',
        'site_logo_dark'    => 'string',
        'site_fav_dark'    => 'string',
        'site_fav'    => 'string',
        'otp_exp_seconds'    => 'integer',

    ];


    public function mailConfig() {

    }
    public function scopeSitename($query, $pageTitle)
    {
        $pageTitle = empty($pageTitle) ? '' : ' - ' . $pageTitle;
        return $this->site_name . $pageTitle;
    }
}
