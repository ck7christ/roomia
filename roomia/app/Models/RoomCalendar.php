<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomCalendar extends Model
{
    //

    protected $fillable = [
        'room_type_id',
        'date',
        'price_per_night',
        'available_units',
        'is_closed',
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
    ];

    /**
     * Loại phòng mà lịch này thuộc về
     */
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}
