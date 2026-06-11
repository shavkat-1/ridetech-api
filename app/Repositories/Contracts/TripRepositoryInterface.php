<?php

namespace App\Repositories\Contracts;

use App\Models\Trip;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TripRepositoryInterface
{
    /**
     * Создать новую поездку
     */
    public function create(array $data): Trip;

    /**
     * Найти поездку по ее ID
     */
    public function findById(int $id): ?Trip;

    /**
     * Обновить данные поездки (например, статус или водителя)
     */
    public function update(Trip $trip, array $data): Trip;
    
    /**
     * Получить отфильтрованный список поездок с пагинацией
     */
    public function getFilteredTrips(array $filters, int $perPage = 10): LengthAwarePaginator;
}