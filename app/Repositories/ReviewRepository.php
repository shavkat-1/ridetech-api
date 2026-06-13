<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Contracts\ReviewRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReviewRepository implements ReviewRepositoryInterface
{
    /**
     * Сохранить новый отзыв
     */
    public function create(array $data): Review
    {
        return Review::create($data);
    }

    /**
     * Получить отзывы для конкретного пользователя (например, водителя)
     */
    public function getReviewsForUser(int $userId, int $perPage = 10)
    {
        return Review::where('reviewee_id', $userId)
            ->with('reviewer:id,name') // Подгружаем имя того, кто оставил отзыв
            ->latest()
            ->paginate($perPage);
    }
}