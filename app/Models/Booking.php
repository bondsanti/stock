<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'bookings';

    public function rooms()
    {
        return $this->hasMany(Room::class, 'rooms_id', 'id');
    }
    public function bookingStatus()
    {
        //return $this->belongsTo(Status_Room::class, 'booking_status', 'id');
        return $this->belongsTo(Status_Room::class, 'booking_status', 'id');
    }
}
