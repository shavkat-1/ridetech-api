<?php

namespace App\Http\Controllers\Api\V1\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Car\CreateCarRequest;
use App\Http\Requests\Api\V1\Car\UpdateCarRequest;
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
        // Передаем ID авторизованного юзера и валидированные данные
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
    public function update(UpdateCarRequest $request, int $carId): JsonResponse
    {
        $car = $this->carService->updateCar(
            $request->user()->id,
            $carId,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Информация об автомобиле успешно обновлена',
            'data' => $car
        ], 200);
    }


    /**
     * Удалить автомобиль
     */
    public function destroy(Request $request, int $carId): JsonResponse
    {
        $this->carService->deleteCar($request->user()->id, $carId);

        return response()->json([
            'success' => true,
            'message' => 'Автомобиль успешно удален'
        ], 200);
    }
}