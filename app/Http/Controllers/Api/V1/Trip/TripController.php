<?php

namespace App\Http\Controllers\Api\V1\Trip;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Trip\CreateTripRequest;
use App\Http\Requests\Api\V1\Trip\UpdateTripRequest;
use App\Models\Trip;
use App\Services\TripService;
use App\Enums\TripStatus;
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
        // Для создания объекта политика обычно не нужна, либо проверяется роль (например, что это Passenger)
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
     * Просмотр деталей конкретной поездки
     */
    public function show(Trip $trip): JsonResponse
    {
        $this->authorize('view', $trip);

        return response()->json([
            'success' => true,
            'data' => $trip
        ], 200);
    }

    /**
     * Пассажир: Обновление поездки (только пока статус pending)
     */
    public function update(UpdateTripRequest $request, Trip $trip): JsonResponse
    {
        $this->authorize('update', $trip);

        $updatedTrip = $this->tripService->updateTrip($trip, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Поездка успешно обновлена',
            'data' => $updatedTrip
        ], 200);
    }

    /**
     * Пассажир: Отмена поездки
     */
    public function cancel(Trip $trip): JsonResponse
    {
        // Вызываем TripPolicy->cancel($user, $trip)
        $this->authorize('cancel', $trip);

        $updatedTrip = $this->tripService->cancelTrip($trip);

        return response()->json([
            'success' => true,
            'message' => 'Поездка успешно отменена',
            'data' => $updatedTrip
        ], 200);
    }

    /**
     * Пассажир: Просмотр истории своих поездок с фильтрацией и пагинацией
     */
    public function index(Request $request): JsonResponse
    {
        // Собираем разрешенные фильтры из запроса
        $filters = $request->only(['status', 'date', 'driver_id']);
    
        // Безопасность: принудительно ограничиваем выборку текущим пассажиром
        $filters['passenger_id'] = $request->user()->id;

        // Передаем фильтры и per_page в сервис
        $perPage = $request->integer('per_page', 10);
        $trips = $this->tripService->getTripsList($filters, $perPage);

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
        $filters = ['status' => TripStatus::PENDING->value];
        $trips = $this->tripService->getTripsList($filters, $request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $trips
        ], 200);
    }

    /**
     * Водитель: Принять поездку
     */
    public function accept(Request $request, Trip $trip): JsonResponse
    {
        // Сначала базовая проверка политики: может ли этот юзер вообще принимать эту поездку?
        $this->authorize('accept', $trip);

        $request->validate([
            'car_id' => ['required', 'integer', 'exists:cars,id']
        ]);

        $updatedTrip = $this->tripService->acceptTrip(
            $request->user()->id,
            $trip,
            $request->input('car_id')
        );

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно приняли заказ',
            'data' => $updatedTrip
        ], 200);
    }

    /**
     * Водитель: Завершить поездку
     */
    public function complete(Trip $trip): JsonResponse
    {
        // Вызываем TripPolicy->complete($user, $trip)
        $this->authorize('complete', $trip);

        $updatedTrip = $this->tripService->completeTrip($trip);

        return response()->json([
            'success' => true,
            'message' => 'Поездка успешно завершена',
            'data' => $updatedTrip
        ], 200);
    }
}