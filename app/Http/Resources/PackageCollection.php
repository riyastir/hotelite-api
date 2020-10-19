<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\HotelProfile;
use Carbon\Carbon;

class PackageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($post) {
                return [
                   'id' => $post->id,
                   'price' => $post->price,
                   'duration_days' => $post->duration_days,
                   'duration_nights' => $post->duration_nights,
                   'validity' => Carbon::parse($post->validity)->format('d-M-Y'),
                   'description' => $post->description,
                   'user'=>new HotelProfile($post->hotel_profile)
                ];
            }),        
        ];
    }

    public function with($request) {
        return [
            'author'  => 'Mohamed Riyas',
            'version' => '0.1.1'
        ];
    }
}
