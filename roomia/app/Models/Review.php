<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'booking_id',
        'rating',
        'comment',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

     public function guest()
    {
        return $this->booking->guest();
    }

     public function room()
    {
        $roomType = $this->booking->roomType;
        return $roomType ? $roomType->room() : null;
    }

     public function host()
    {
        $roomType = $this->booking->roomType;
        $room     = $roomType ? $roomType->room : null;

        return $room ? $room->host() : null;
    }
}
