<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    //
    protected $fillable = [
        'room_id',
        'file_path',
        'is_cover',
        'sort_order',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
