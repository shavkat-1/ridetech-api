<?php

namespace App\Http\Controllers\Api\V1\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Car\CreateCarRequest;
use App\Http\Requests\Api\V1\Car\UpdateCarRequest;
use App\Models\Car;
use App\Services\CarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct(
        protected CarService $carService
    ) {}

    /**
     * Добавить автомобиль
     */
    public function store(CreateCarRequest $request): JsonResponse
    {
        $car = $this->carService->addCar(
            $request->user()->id, 
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Автомобиль успешно добавлен',
            'data' => $car
        ], 201);
    }

    /**
     * Получить список автомобилей текущего водителя
     */
    public function index(Request $request): JsonResponse
    {
        $cars = $this->carService->getDriverCars($request->user()->id);

        return response()->json([
            'success' => true,
            'data' => $cars
        ], 200);
    }

    /**
     * Обновить информацию об автомобиле
     */
    public function update(UpdateCarRequest $request, Car $car): JsonResponse
    {
        // Вызываем CarPolicy->update($user, $car)
        $this->authorize('update', $car);

        $updatedCar = $this->carService->updateCar($car, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Информация об автомобиле успешно обновлена',
            'data' => $updatedCar
        ], 200);
    }

    /**
     * Удалить автомобиль
     */
    public function destroy(Car $car): JsonResponse
    {
        // Вызываем CarPolicy->delete($user, $car)
        $this->authorize('delete', $car);

        $this->carService->deleteCar($car);

        return response()->json([
            'success' => true,
            'message' => 'Автомобиль успешно удален'
        ], 200);
    }
}