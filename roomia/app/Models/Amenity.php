<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    //
    protected $fillable = [
        'name',
        'code',
        'group',
        'icon_class',
        'is_active',
        'sort_order',
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_amenity')
            ->withTimestamps();
    }
}
