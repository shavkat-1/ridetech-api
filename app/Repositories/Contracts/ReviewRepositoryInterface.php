<?php

namespace App\Repositories\Contracts;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

interface ReviewRepositoryInterface
{
    /**
     * Сохранить новый отзыв
     */
    public function create(array $data): Review;

    /**
     * Получить отзывы для конкретного пользователя (например, водителя)
     */
    public function getReviewsForUser(int $userId, int $perPage = 10);
}