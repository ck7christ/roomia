<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAddress extends Model
{
    //
    protected $fillable = [
        'room_id',
        'country_id',
        'city_id',
        'district_id',
        'street',
        'zip_code',
        'formatted_address',
        'lat',
        'lng',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
