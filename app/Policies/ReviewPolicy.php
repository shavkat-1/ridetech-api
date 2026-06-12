<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Trip;
use App\Models\Review;
use App\Enums\TripStatus;

class ReviewPolicy
{
    /**
     * Оставлять отзывы может только пассажир, который совершил эту поездку и не делал этого ранее
     */
    public function create(User $user, Trip $trip): bool
    {
        // 1. Поездка должна быть строго завершенной
        if ($trip->status !== TripStatus::COMPLETED) {
            return false;
        }

        // 2. Текущий пользователь должен быть именно пассажиром этой поездки
        if ($user->id !== $trip->passenger_id) {
            return false;
        }

        // 3. Защита от дубликатов: проверяем, не оставлял ли пассажир отзыв на эту поездку ранее
        $alreadyReviewed = Review::where('trip_id', $trip->id)
            ->where('reviewer_id', $user->id)
            ->exists();

        if ($alreadyReviewed) {
            return false; // Laravel автоматически выкинет 403 Forbidden
        }

        return true;
    }
}