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
    public function updateCar(\App\Models\Car $car, array $data): \App\Models\Car
    {
        // Никаких проверок прав и findById! Только обновление данных через репозиторий
        return $this->carRepository->update($car, $data);
    }

    /**
     * Удалить автомобиль
    */
    public function deleteCar(\App\Models\Car $car): bool
    {
        // Никаких проверок прав и findById! Только удаление через репозиторий
        return $this->carRepository->delete($car);
    }
}