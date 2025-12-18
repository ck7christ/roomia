<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    //
    protected $fillable = [
        'room_id',
        'name',
        'description',
        'max_guests',
        'total_units',
        'price_per_night',
        'status',
    ];

    protected $casts = [
        'max_guests' => 'integer',
        'total_units' => 'integer',
        'price_per_night' => 'decimal:2',
    ];

    /**
     * Room (khách sạn/property) mà loại phòng này thuộc về
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Lịch theo ngày / tồn kho cho loại phòng này
     */
    public function calendars()
    {
        return $this->hasMany(RoomCalendar::class, 'room_type_id');
    }

    /**
     * Các booking của loại phòng này
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_type_id');
    }

}
