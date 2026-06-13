<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
use App\Enums\UserRole;   // Импортируем Enum ролей
use App\Enums\TripStatus; // Импортируем Enum статусов поездки
use Illuminate\Auth\Access\Response;

class TripPolicy
{

    /**
     * Может ли пользователь просматривать детали поездки?
     * Доступно пассажиру-владельцу, назначенному водителю,
     * или любому водителю, если поездка еще свободна (pending).
     */
    public function view(User $user, Trip $trip): bool
    {
        return $user->id === $trip->passenger_id
            || $user->id === $trip->driver_id
            || ($user->role === UserRole::DRIVER && $trip->status === TripStatus::PENDING);
    }

    /**
     * Может ли пассажир обновить поездку?
     * Только владелец и только пока поездка еще не принята водителем (pending).
     */
    public function update(User $user, Trip $trip): bool
    {
        return $user->id === $trip->passenger_id
            && $trip->status === TripStatus::PENDING;
    }

    /**
     * Может ли пассажир отменить поездку?
     * Только если он является пассажиром и поездка еще не началась (статус pending).
     */
    public function cancel(User $user, Trip $trip): bool
    {
        return $user->id === $trip->passenger_id 
            && $trip->status === TripStatus::PENDING;
    }

    /**
     * Может ли водитель принять поездку?
     * Только если у пользователя роль driver, у него есть машина, а поездка еще свободна.
     */
    public function accept(User $user, Trip $trip): bool
    {

        return $user->role === UserRole::DRIVER 
            && $trip->status === TripStatus::PENDING;
    }

    /**
     * Может ли водитель завершить поездку?
     * Только если этот же водитель её принял и она сейчас выполняется (in_progress).
     */
    public function complete(User $user, Trip $trip): bool
{

    return $user->id === $trip->driver_id 
        && $trip->status === TripStatus::IN_PROGRESS;
}


    public function addReview(User $user, Trip $trip): bool
    {
        // 1. Поездка должна быть завершена
        if ($trip->status !== \App\Enums\TripStatus::COMPLETED) {
            return false;
       }

        // 2. Пользователь — пассажир этой поездки
        if ($user->id !== $trip->passenger_id) {
            return false;
        }

        // 3. Проверка на дубликат
        $alreadyReviewed = \App\Models\Review::where('trip_id', $trip->id)
        ->where('reviewer_id', $user->id)
        ->exists();

            return !$alreadyReviewed;
        }
}