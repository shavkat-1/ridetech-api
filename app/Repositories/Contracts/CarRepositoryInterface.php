<?php

namespace App\Repositories\Contracts;

use App\Models\Car;
use Illuminate\Database\Eloquent\Collection;

interface CarRepositoryInterface
{
    /**
     * Создать новую запись автомобиля
     */
    public function create(array $data): Car;

    /**
     * Получить все автомобили конкретного водителя
     */
    public function getByDriverId(int $driverId): Collection;


    /**
    * Найти автомобиль по ID
    */

    public function findById(int $id): ?Car;

    /**
     * Обновить данные автомобиля
     */
    public function update(Car $car, array $data): ?Car;

    /**
     * Удалить автомобиль
     */
    public function delete(Car $car): bool;



}