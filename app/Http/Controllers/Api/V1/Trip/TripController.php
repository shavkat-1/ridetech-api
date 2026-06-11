<?php

namespace App\Http\Controllers\Api\V1\Trip;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Trip\CreateTripRequest;
use App\Services\TripService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function __construct(
        protected TripService $tripService
    ) {}

    /**
     * Пассажир: Создание поездки
     */
    public function store(CreateTripRequest $request): JsonResponse
    {
        $trip = $this->tripService->createTrip(
            $request->user()->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Поездка успешно создана, ожидайте водителя',
            'data' => $trip
        ], 201);
    }

    /**
     * Пассажир: Отмена поездки
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        $trip = $this->tripService->cancelTrip($request->user()->id, $id);

        return response()->json([
            'success' => true,
            'message' => 'Поездка успешно отменена',
            'data' => $trip
        ], 200);
    }

    /**
     * Пассажир: Просмотр истории своих поездок
     */
    public function index(Request $request): JsonResponse
    {
        // Принудительно фильтруем только по текущему пользователю-пассажиру
        $filters = ['passenger_id' => $request->user()->id];
        
        $trips = $this->tripService->getTripsList($filters, $request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $trips
        ], 200);
    }


    /**
     * Водитель: Просмотр доступных поездок (статус "ожидает водителя")
     */
    public function available(Request $request): JsonResponse
    {
        // Фильтруем строго по статусу PENDING (ожидание)
        $filters = ['status' => \App\Enums\TripStatus::PENDING->value];
        
        $trips = $this->tripService->getTripsList($filters, $request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $trips
        ], 200);
    }

    /**
     * Водитель: Принять поездку
     */
    public function accept(Request $request, int $id): JsonResponse
    {
        // Для MVP передаем car_id прямо в запросе, либо можно автоматом брать первую машину водителя
        $request->validate([
            'car_id' => ['required', 'integer', 'exists:cars,id']
        ]);

        $trip = $this->tripService->acceptTrip(
            $request->user()->id,
            $id,
            $request->input('car_id')
        );

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно приняли заказ',
            'data' => $trip
        ], 200);
    }

    /**
     * Водитель: Завершить поездку
     */
    public function complete(Request $request, int $id): JsonResponse
    {
        $trip = $this->tripService->completeTrip($request->user()->id, $id);

        return response()->json([
            'success' => true,
            'message' => 'Поездка успешно завершена',
            'data' => $trip
        ], 200);
    }
}