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
     * Получить отфильтрованные поездки с пагинацией
     */
    public function getFilteredTrips(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Trip::query()->with(['passenger', 'driver', 'car']);

        // Фильтр по статусу
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Фильтр по пассажиру
        if (!empty($filters['passenger_id'])) {
            $query->where('passenger_id', $filters['passenger_id']);
        }

        // Фильтр по водителю
        if (!empty($filters['driver_id'])) {
            $query->where('driver_id', $filters['driver_id']);
        }

        // Фильтр по конкретной дате отправления (Y-m-d)
        if (!empty($filters['date'])) {
            $query->whereDate('departure_time', $filters['date']);
        }

        // Свежие поездки первыми
        return $query->latest()->paginate($perPage);
    }
}