<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'brand',
        'model',
        'year',
        'license_plate',
    ];


    /**
     * Кастуем год выпуска к целому числу
     */
    protected $casts = [
        'year' => 'integer',
    ];


    
    // водитель, которому принадлежит автомобиль
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
