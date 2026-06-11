<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\UserRole;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role', // Роль пользователя (пассажир или водитель)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',  // Приведение даты верификации email к объекту Carbon
        'password' => 'hashed',   // Автоматическое хеширование пароля при сохранении в базу данных
        'role' => UserRole::class, // Приведение роли к enum UserRole. Превратит строку из базы в строгий объект Enum
    ];
    


    // поездки, в которых пользователь выступает водителем
    public function tripsAsDriver()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }


    // поездки, в которых пользователь выступает пассажиром
    public function tripsAsPassenger()
    {
        return $this->hasMany(Trip::class, 'passenger_id');
    }


    public function cars()
    {
        return $this->hasMany(Car::class, 'driver_id');
    }
}
