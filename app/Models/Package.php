<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'price', 'duration_days', 'duration_nights', 'validity', 'description'];

    /**
     * Relation with User
     */
    public function hotel_profile()
    {
        return $this->belongsTo('App\Models\HotelProfile','user_id','user_id');
    }
}
