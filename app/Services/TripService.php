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
    public function getTripsList(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->tripRepository->getFilteredTrips($filters, $perPage);
    }

    /**
     * Отменить поездку пассажиром
     */
    public function cancelTrip(Trip $trip): Trip
    {
        // Проверка прав ушла в TripPolicy@cancel. 
        // Здесь проверяем исключительно бизнес-логику статуса:
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
    public function acceptTrip(int $driverId, Trip $trip, int $carId): Trip
    {
        // Проверка прав и роли ушла в TripPolicy@accept.
        // Здесь проверяем бизнес-логику переходов статусов и защиту от самозаказа:
        if ($trip->status !== TripStatus::PENDING) {
            abort(400, 'Эта поездка уже не доступна для взятия.');
        }

        if ($trip->passenger_id === $driverId) {
            abort(400, 'Вы не можете принять собственную поездку.');
        }

        return $this->tripRepository->update($trip, [
            'driver_id' => $driverId,
            'car_id'    => $carId,
            'status'    => TripStatus::IN_PROGRESS
        ]);
    }

    /**
     * Водитель: Завершить поездку
     */
    public function completeTrip(Trip $trip): Trip
    {
        // Проверка «Тот ли это водитель?» ушла в TripPolicy@complete.
        // Здесь валидируем только статус модели:
        if ($trip->status !== TripStatus::IN_PROGRESS) {
            abort(400, 'Можно завершить только выполняемую поездку.');
        }

        return $this->tripRepository->update($trip, [
            'status' => TripStatus::COMPLETED
        ]);
    }
}