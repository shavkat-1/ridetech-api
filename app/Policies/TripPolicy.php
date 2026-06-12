<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TripPolicy
{

/**
     * Может ли пассажир отменить поездку?
     * Только если он является пассажиром и поездка еще не началась (статус pending).
     */

    public function cancel(User $user, Trip $trip): bool
    {
        return $user->id === $trip->passenger_id && $trip->status === 'pending';
    }


    /**
     * Может ли водитель принять поездку?
     * Только если у пользователя роль driver, у него есть машина, а поездка еще свободна.
     */
    public function accept(User $user, Trip $trip): bool
    {
        return $user->role === 'driver' && $trip->status === 'pending';
    }



    
}
