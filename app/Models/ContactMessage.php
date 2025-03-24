<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ContactMessage extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function getEditDataAttribute() {
        $data = [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'reply'     => ($this->reply == true) ? 1 : 0,
            'message'   => $this->message,
        ];

        return json_encode($data);
    }
}
