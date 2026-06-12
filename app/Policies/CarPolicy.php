<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CarPolicy
{
    
    /**
     * разрешить ли пользователю просматривать список машин
     */
    public function update(User $user, Car $car): bool
    {
        return $user->id === $car->driver_id;
    }

    /**
     * разрешить ли пользователю удалять машину
     */
    public function delete(User $user, Car $car): bool
    {
        return $user->id === $car->driver_id;
    }

    
}
