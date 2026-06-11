<?php

namespace App\Services;

use App\Enums\TripStatus;
use App\Models\Trip;
use App\Repositories\Contracts\TripRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TripService
{
    public function __construct(
        protected TripRepositoryInterface $tripRepository
    ) {}

    /**
     * Создать новую поездку
     */
    public function createTrip(int $passengerId, array $data): Trip
    {
        $data['passenger_id'] = $passengerId;
        $data['status'] = TripStatus::PENDING; // По умолчанию статус ожидания

        return $this->tripRepository->create($data);
    }


    /**
     * Получить историю или отфильтрованный список поездок
     */
    public function getTripsList(array $filters, int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->tripRepository->getFilteredTrips($filters, $perPage);
    }



    /**
     * Отменить поездку пассажиром
     */
    public function cancelTrip(int $passengerId, int $tripId): Trip
    {
        $trip = $this->tripRepository->findById($tripId);

        if (!$trip || $trip->passenger_id !== $passengerId) {
            abort(403, 'Вы не являетесь автором этой поездки.');
        }

        // Отменить можно только если водитель еще не принял заказ
        if ($trip->status !== TripStatus::PENDING) {
            abort(400, 'Нельзя отменить поездку, которая уже выполняется или завершена.');
        }

        return $this->tripRepository->update($trip, [
            'status' => TripStatus::CANCELLED
        ]);
    }

    /**
     * Водитель: Принять поездку
     */
    public function acceptTrip(int $driverId, int $tripId, int $carId): Trip
    {
        $trip = $this->tripRepository->findById($tripId);

        if (!$trip) {
            abort(404, 'Поездка не найдена.');
        }

        // Проверяем, что поездку еще никто не взял
        if ($trip->status !== TripStatus::PENDING) {
            abort(400, 'Эта поездка уже не доступна для взятия.');
        }

        // Водитель не может везти сам себя
        if ($trip->passenger_id === $driverId) {
            abort(400, 'Вы не можете принять собственную поездку.');
        }

        return $this->tripRepository->update($trip, [
            'driver_id' => $driverId,
            'car_id' => $carId,
            'status' => TripStatus::IN_PROGRESS // Используем твой статус из Enum
        ]);
    }

    /**
     * Водитель: Завершить поездку
     */
    public function completeTrip(int $driverId, int $tripId): Trip
    {
        $trip = $this->tripRepository->findById($tripId);

        if (!$trip || $trip->driver_id !== $driverId) {
            abort(403, 'Вы не являетесь водителем этой поездки.');
        }

        if ($trip->status !== TripStatus::IN_PROGRESS) { // Проверяем на статус IN_PROGRESS
            abort(400, 'Можно завершить только принятую и выполняемую поездку.');
        }

        return $this->tripRepository->update($trip, [
            'status' => TripStatus::COMPLETED
        ]);
    }
}