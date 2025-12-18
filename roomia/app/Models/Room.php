<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    protected $fillable = [
        'host_id',
        'title',
        'description',
        'status',
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function address()
    {
        return $this->hasOne(RoomAddress::class);
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function coverImage()
    {
        return $this->hasOne(RoomImage::class)->where('is_cover', true);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'room_amenity')
            ->withTimestamps();
    }

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }
    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

}
