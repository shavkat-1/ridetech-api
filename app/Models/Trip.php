<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TripStatus;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'passenger_id',
        'driver_id',
        'car_id',
        'origin',
        'destination',
        'departure_time',
        'preferences',
        'status',
    ];


    protected $casts = [
        'departure_time' => 'datetime',
        'status' => TripStatus::class, // Приведение статуса к enum TripStatus
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }


        // отзывы, связанные с этой поездкой
    public function reviews()
    {
        return $this->hasMany(Review::class, 'trip_id');
    }
}
