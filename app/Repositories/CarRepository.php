<?php

namespace App\Repositories;

use App\Models\Car;
use App\Repositories\Contracts\CarRepositoryInterface; // <-- Импортируем интерфейс
use Illuminate\Database\Eloquent\Collection;

class CarRepository implements CarRepositoryInterface // <-- Реализуем контракт
{
    /**
     * Создать новую запись автомобиля
     */
    public function create(array $data): Car
    {
        return Car::create($data);
    }

    /**
     * Получить все автомобили конкретного водителя
     */
    public function getByDriverId(int $driverId): Collection
    {
        return Car::where('driver_id', $driverId)->get();
    }


    /**
     * Найти автомобиль по ID
     */
    public function findById(int $id): ?Car
    {
        return Car::find($id);
    }


    /**
     * Обновить данные автомобиля
     */
    public function update(Car $car, array $data): Car
    {
        $car->update($data);
        return $car;
        }
        
    

    /**
     * Удалить автомобиль
     */
    public function delete(Car $car): bool
    {
        return $car->delete();
    }
}