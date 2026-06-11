<?php

namespace App\Services;

use App\Enums\TripStatus;
use App\Models\Review;
use App\Repositories\ReviewRepository;
use App\Repositories\Contracts\TripRepositoryInterface;

class ReviewService
{
    public function __construct(
        protected ReviewRepository $reviewRepository,
        protected TripRepositoryInterface $tripRepository
    ) {}

    /**
     * Создать отзыв после завершения поездки
     */
    public function createReview(array $data, int $tripId): Review
    {
        // Вытаскиваем ID авторизованного пассажира напрямую из хелпера Laravel
        $passengerId = auth()->id();

        $trip = $this->tripRepository->findById($tripId);

        // 1. Проверяем существование поездки и роль участника
        if (!$trip || $trip->passenger_id !== $passengerId) {
            abort(403, 'Вы не можете оставить отзыв к этой поездке.');
        }

        // 2. Проверяем, что поездка завершена
        if ($trip->status !== TripStatus::COMPLETED) {
            abort(400, 'Нельзя оставить отзыв к незавершенной поездке.');
        }

        // 3. Проверяем, есть ли у поездки водитель
        if (!$trip->driver_id) {
            abort(400, 'У этой поездки не было водителя.');
        }

        // 4. Проверяем, не оставлял ли пассажир отзыв на эту поездку ранее
        $alreadyReviewed = Review::where('trip_id', $tripId)
            ->where('reviewer_id', $passengerId)
            ->exists();

        if ($alreadyReviewed) {
            abort(400, 'Вы уже оставили отзыв для этой поездки.');
        }

        // Формируем данные для записи
        $reviewData = [
            'trip_id'     => $tripId,
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