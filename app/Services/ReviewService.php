<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Trip;
use App\Repositories\ReviewRepository;
use App\Repositories\Contracts\TripRepositoryInterface;

class ReviewService
{
    public function __construct(
        protected ReviewRepository $reviewRepository,
        protected TripRepositoryInterface $tripRepository
    ) {}

    /**
     * Создать отзыв после завершения поездки (Вызывается только для пассажиров)
     */
    public function createReview(int $passengerId, Trip $trip, array $data): Review
    {
        // Проверки существования поездки, роли пассажира, статуса COMPLETED 
        // и дубликатов отзывов полностью ушли в ReviewPolicy и Route Model Binding.

        // Бизнес-проверка: на случай форс-мажора, если у завершенной поездки почему-то нет водителя
        if (!$trip->driver_id) {
            abort(400, 'У этой поездки не было водителя.');
        }

        // Формируем данные для записи (используем твои оригинальные reviewer/reviewee поля)
        $reviewData = [
            'trip_id'     => $trip->id,
            'reviewer_id' => $passengerId,
            'reviewee_id' => $trip->driver_id, // Отзыв пишется водителю
            'rating'      => $data['rating'],
            'comment'     => $data['comment'] ?? null,
        ];

        return $this->reviewRepository->create($reviewData);
    }

    /**
     * Получить список отзывов конкретного водителя
     */
    public function getReviewsForDriver(int $driverId)
    {
        return $this->reviewRepository->getReviewsForUser($driverId);
    }
}