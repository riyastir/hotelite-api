<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelProfile extends JsonResource
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
            'name' => $this->name,
            'locality' => $this->locality,
            'city'=>$this->city,
            'state'=>$this->state,
            'lat'=>$this->lat,
            'lng'=>$this->lng,
            'logo'=>$this->logo!=''? url($this->logo):"",
            'country'=>$this->country,
            'country_code'=>$this->country_code
        ];
    }

    public function with($request) {
        return [
            'author'  => 'Mohamed Riyas',
            'version' => '0.1.1'
        ];
    }
}
