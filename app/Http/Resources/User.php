<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'email' => $this->email,
            'role' => $this->roles->pluck('name')->toArray()[0],
        ];
    }

    public function with($request) {
        return [
            'author'  => 'Mohamed Riyas',
            'version' => '0.1.1'
        ];
    }
}
