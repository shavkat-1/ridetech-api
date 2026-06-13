<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Trip;
use App\Repositories\ReviewRepository;
use App\Repositories\Contracts\TripRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     * Получить пагинированный список отзывов конкретного водителя
     */
    public function getReviewsForDriver(int $driverId, int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        // Строго проверяем, чтобы здесь было 'reviewee_id'
        return \App\Models\Review::where('reviewee_id', $driverId) 
            ->with('reviewer:id,name') 
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Получить отзывы для конкретной поездки
     */
    public function getReviewsForTrip(Trip $trip)
    {
        // Запрашиваем через репозиторий или напрямую у модели Review, 
        // подтягивая данные того, кто оставил отзыв (reviewer)
        return Review::where('trip_id', $trip->id)
            ->with('reviewer:id,name') 
            ->get();
}
}