<?php

namespace App\Http\Resources;

use App\Models\Admin\Services\Service;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin Service */
class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'base_url'  => url('/'),
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => url(asset('/public/images/services/'.$this->image)),
            'status' => $this->status,
            'size' => $this->size,
            'number_bottles' => $this->number_bottles,
            'price' => $this->price,
        ];
    }
}
