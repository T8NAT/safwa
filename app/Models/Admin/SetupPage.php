<?php

namespace App\Models\Admin;

use App\Constants\GlobalConst;
use App\Constants\LanguageConst;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetupPage extends Model
{
    use HasFactory;
    protected $casts = [
        'title'   => 'object',
        'details' => 'object'
    ];
    protected $guarded = ['id'];

}
