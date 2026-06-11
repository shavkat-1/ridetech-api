<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Review\ReviewRequest;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

/**
 * Оставить отзыв после завершения поездки
 */
public function store(ReviewRequest $request, $tripId)
{
    $result = $this->reviewService->createReview($request->validated(), $tripId);

    return response()->json([
        'success' => true,
        'message' => 'Отзыв успешно создан',
        'data' => [
            'review' => $result,
        ]
    ], 201);

}

/**
     * GET /api/v1/reviews/{driver_id}
     * Получить список отзывов конкретного водителя
     */
    public function index($driverId)
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