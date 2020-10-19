<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\HotelProfile;
use Carbon\Carbon;

class Package extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'price' => $this->price,
            'duration_days' => $this->duration_days,
            'duration_nights'=>$this->duration_nights,
            'validity'=>Carbon::parse($this->validity)->format('d-M-Y'),
            'description'=>$this->description,
            'user'=>new HotelProfile($this->hotel_profile)
        ];
    }

    public function with($request) {
        return [
            'author'  => 'Mohamed Riyas',
            'version' => '0.1.1'
        ];
    }
}
