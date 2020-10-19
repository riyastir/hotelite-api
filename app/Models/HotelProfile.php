<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelProfile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'locality', 'city', 'state', 'lat', 'lng', 'logo', 'country', 'country_code'];
}
