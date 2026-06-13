<?php

namespace App\Repositories;

use App\Models\Trip;
use App\Repositories\Contracts\TripRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TripRepository implements TripRepositoryInterface
{
    public function create(array $data): Trip
    {
        return Trip::create($data);
    }

    public function findById(int $id): ?Trip
    {
        return Trip::find($id);
    }

    public function update(Trip $trip, array $data): Trip
    {
        $trip->update($data);
        return $trip;
    }

    /**
      * Получить отфильтрованные и пагинированные поездки из БД
     */
    public function getFilteredTrips(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Trip::query();

        // Фильтр по пассажиру (всегда активен для этого эндпоинта)
        if (!empty($filters['passenger_id'])) {
            $query->where('passenger_id', $filters['passenger_id']);
        }

        // Динамический фильтр по статусу (Enum или строка)
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Динамический фильтр по дате (игнорируем время, смотрим только день)
        if (!empty($filters['date'])) {
            $query->whereDate('departure_time', $filters['date']);
        }

        // Динамический фильтр по водителю
        if (!empty($filters['driver_id'])) {
            $query->where('driver_id', $filters['driver_id']);
        }

        // Свежие поездки вверху, подтягиваем связанные машины, если нужно
        return $query->with(['car'])->latest()->paginate($perPage);
    }

}