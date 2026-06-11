<?php

namespace App\Services;

use App\Repositories\Contracts\CarRepositoryInterface;
use App\Models\Car;
use Illuminate\Database\Eloquent\Collection;

class CarService
{
    public function __construct(
        protected CarRepositoryInterface $carRepository // <-- Внедряем интерфейс
    ) {}

    /**
     * Бизнес-логика добавления машины
     */
    public function addCar(int $driverId, array $data): Car
    {
        $data['driver_id'] = $driverId;

        return $this->carRepository->create($data);
    }

    /**
     * Получить список машин водителя
     */
    public function getDriverCars(int $driverId): Collection
    {
        return $this->carRepository->getByDriverId($driverId);
    }


    /**
     * Обновить информацию об автомобиле
     */
    public function updateCar(int $driverId, int $carId, array $data): Car
    {
        $car = $this->carRepository->findById($carId);

        if (!$car || $car->driver_id !== $driverId) {
            abort(403, 'Вы не являетесь владельцем этого транспортного средства.');
        }

        return $this->carRepository->update($car, $data);
    }

    /**
     * Удалить автомобиль
     */
    public function deleteCar(int $driverId, int $carId): void
    {
        $car = $this->carRepository->findById($carId);

        if (!$car || $car->driver_id !== $driverId) {
            abort(403, 'Вы не являетесь владельцем этого транспортного средства.');
        }

        $this->carRepository->delete($car);
    }
}