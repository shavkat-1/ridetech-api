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
        $this->authorize('create', $trip);

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
     * Получить список отзывов конкретного водителя
     */
    public function index(int $driverId): JsonResponse
    {
        $reviews = $this->reviewService->getReviewsForDriver($driverId);

        return response()->json([
            'success' => true,
            'data' => [
                'reviews' => $reviews,
            ]
        ]);
    }
}