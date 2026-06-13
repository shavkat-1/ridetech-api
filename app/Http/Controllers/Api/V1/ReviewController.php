<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Review\ReviewRequest;
use App\Models\Trip;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    /**
     * Оставить отзыв после завершения поездки (только для пассажира)
     */
    public function store(ReviewRequest $request, Trip $trip): JsonResponse
    {
        // Вызывает ReviewPolicy@create
        $this->authorize('addReview', $trip);

        $result = $this->reviewService->createReview(
            $request->user()->id,
            $trip,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Отзыв успешно создан',
            'data' => [
                'review' => $result,
            ]
        ], 201);
    }

    /**
     * Получить список отзывов конкретного водителя (с пагинацией)
     */
    public function index(Trip $trip): JsonResponse
    {
        // Бизнес-чек: если у поездки нет водителя, сразу возвращаем ошибку
        if (!$trip->driver_id) {
            return response()->json([
                'success' => false,
                'message' => 'У этой поездки не найден ID водителя.'
            ], 400);
        }

        // Передаем управление в сервис, запрашивая пагинацию по 10 отзывов
        $reviews = $this->reviewService->getReviewsForDriver($trip->driver_id, 10);

        return response()->json([
            'success' => true,
            // Laravel автоматически развернет LengthAwarePaginator в JSON вместе с мета-данными пагинации
            'data' => $reviews 
        ]);
    } // Скобка была пропущена, теперь всё на месте
}