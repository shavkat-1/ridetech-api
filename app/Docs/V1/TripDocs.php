<?php

namespace App\Docs\V1;

class TripDocs
{
    /**
     * @OA\Get(
     * path="/trips",
     * operationId="getPassengerTripsHistory",
     * tags={"Trips - Passenger"},
     * summary="Пассажир: Просмотр истории своих поездок",
     * description="Возвращает пагинированный список всех поездок, созданных текущим авторизованным пассажиром.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="per_page",
     * in="query",
     * description="Количество элементов на странице (по умолчанию 10)",
     * required=false,
     * @OA\Schema(type="integer", example=10)
     * ),
     * @OA\Parameter(
     * name="status",
     * in="query",
     * description="Фильтр по статусу поездки",
     * required=false,
     * @OA\Schema(type="string", enum={"pending","in_progress","completed","cancelled"}, example="completed")
     * ),
     * @OA\Parameter(
     * name="date",
     * in="query",
     * description="Фильтр по дате отправления (Y-m-d)",
     * required=false,
     * @OA\Schema(type="string", format="date", example="2026-06-15")
     * ),
     * @OA\Parameter(
     * name="driver_id",
     * in="query",
     * description="Фильтр по ID водителя",
     * required=false,
     * @OA\Schema(type="integer", example=3)
     * ),
     * @OA\Response(
     * response=200,
     * description="Успешный запрос истории поездок",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="passenger_id", type="integer", example=5, description="ID пассажира (users.id)"),
     * @OA\Property(property="driver_id", type="integer", example=3, nullable=true, description="ID водителя (users.id)"),
     * @OA\Property(property="car_id", type="integer", example=1, nullable=true, description="ID автомобиля (cars.id)"),
     * @OA\Property(property="origin", type="string", example="Душанбе, ул. Рудаки 15"),
     * @OA\Property(property="destination", type="string", example="Худжанд, 19-й микрорайон"),
     * @OA\Property(property="departure_time", type="string", example="2026-06-15 14:00:00"),
     * @OA\Property(property="preferences", type="string", example="С кондиционером", nullable=true),
     * @OA\Property(property="status", type="string", example="completed", description="Текущий статус из TripStatus Enum"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2026-06-12 12:00:00"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2026-06-12 12:45:00")
     * )
     * )
     * )
     * ),
     * @OA\Response(response=401, description="Неавторизован")
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     * path="/trips",
     * operationId="createTrip",
     * tags={"Trips - Passenger"},
     * summary="Пассажир: Создание поездки",
     * description="Позволяет авторизованному пассажиру создать новую заявку на поездку. По умолчанию выставляется статус PENDING.",
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"origin", "destination", "departure_time"},
     * @OA\Property(property="origin", type="string", example="Душанбе, ул. Рудаки 15", description="Место отправления"),
     * @OA\Property(property="destination", type="string", example="Худжанд, 19-й микрорайон", description="Место назначения"),
     * @OA\Property(property="departure_time", type="string", example="2026-06-15 14:00:00", description="Время отправления в формате Y-m-d H:i:s"),
     * @OA\Property(property="preferences", type="string", example="С кондиционером, без курящих", nullable=true, description="Предпочтения пассажира")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Поездка успешно создана, ожидайте водителя",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Поездка успешно создана, ожидайте водителя"),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="passenger_id", type="integer", example=5),
     * @OA\Property(property="driver_id", type="integer", example=null, nullable=true),
     * @OA\Property(property="car_id", type="integer", example=null, nullable=true),
     * @OA\Property(property="origin", type="string", example="Душанбе, ул. Рудаки 15"),
     * @OA\Property(property="destination", type="string", example="Худжанд, 19-й микрорайон"),
     * @OA\Property(property="departure_time", type="string", example="2026-06-15 14:00:00"),
     * @OA\Property(property="preferences", type="string", example="С кондиционером, без курящих", nullable=true),
     * @OA\Property(property="status", type="string", example="pending"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2026-06-12 15:00:00"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2026-06-12 15:00:00")
     * )
     * )
     * ),
     * @OA\Response(response=401, description="Неавторизован"),
     * @OA\Response(response=422, description="Ошибка валидации входных данных")
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     * path="/trips/{id}",
     * operationId="getTripDetails",
     * tags={"Trips - Passenger"},
     * summary="Просмотр деталей поездки",
     * description="Возвращает детали конкретной поездки. Доступно пассажиру-владельцу, назначенному водителю, либо любому водителю, если поездка еще свободна (pending).",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID поездки (trips.id)",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Детали поездки успешно получены",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="passenger_id", type="integer", example=5),
     * @OA\Property(property="driver_id", type="integer", example=3, nullable=true),
     * @OA\Property(property="car_id", type="integer", example=1, nullable=true),
     * @OA\Property(property="origin", type="string", example="Душанбе, ул. Рудаки 15"),
     * @OA\Property(property="destination", type="string", example="Худжанд, 19-й микрорайон"),
     * @OA\Property(property="departure_time", type="string", example="2026-06-15 14:00:00"),
     * @OA\Property(property="preferences", type="string", example="С кондиционером, без курящих", nullable=true),
     * @OA\Property(property="status", type="string", example="pending"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2026-06-12 15:00:00"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2026-06-12 15:45:00")
     * )
     * )
     * ),
     * @OA\Response(response=401, description="Неавторизован"),
     * @OA\Response(response=403, description="Нет доступа к этой поездке"),
     * @OA\Response(response=404, description="Поездка не найдена")
     * )
     */
    public function show() {}

    /**
     * @OA\Put(
     * path="/trips/{id}",
     * operationId="updateTrip",
     * tags={"Trips - Passenger"},
     * summary="Пассажир: Обновление поездки",
     * description="Позволяет пассажиру-владельцу обновить детали своей поездки (адрес, время, предпочтения), если она еще не была принята водителем (статус PENDING). Все поля опциональны.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID поездки (trips.id)",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="origin", type="string", example="Душанбе, ул. Рудаки 15"),
     * @OA\Property(property="destination", type="string", example="Худжанд, 19-й микрорайон"),
     * @OA\Property(property="departure_time", type="string", example="2026-06-15 15:00:00"),
     * @OA\Property(property="preferences", type="string", example="С кондиционером, без курящих", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Поездка успешно обновлена",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Поездка успешно обновлена"),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="passenger_id", type="integer", example=5),
     * @OA\Property(property="driver_id", type="integer", example=null, nullable=true),
     * @OA\Property(property="car_id", type="integer", example=null, nullable=true),
     * @OA\Property(property="origin", type="string", example="Душанбе, ул. Рудаки 15"),
     * @OA\Property(property="destination", type="string", example="Худжанд, 19-й микрорайон"),
     * @OA\Property(property="departure_time", type="string", example="2026-06-15 15:00:00"),
     * @OA\Property(property="preferences", type="string", example="С кондиционером, без курящих", nullable=true),
     * @OA\Property(property="status", type="string", example="pending"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2026-06-12 15:00:00"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2026-06-12 15:45:00")
     * )
     * )
     * ),
     * @OA\Response(response=400, description="Поездку нельзя изменить (уже принята водителем или завершена)"),
     * @OA\Response(response=401, description="Неавторизован"),
     * @OA\Response(response=403, description="Нет доступа к редактированию этой поездки"),
     * @OA\Response(response=404, description="Поездка не найдена"),
     * @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function update() {}

    /**
     * @OA\Post(
     * path="/trips/{id}/cancel",
     * operationId="cancelTrip",
     * tags={"Trips - Passenger"},
     * summary="Пассажир: Отмена поездки",
     * description="Позволяет пассажиру отменить свою активную поездку. Статус изменяется на CANCELLED.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID поездки (trips.id)",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Поездка успешно отменена",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Поездка успешно отменена"),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="passenger_id", type="integer", example=5),
     * @OA\Property(property="driver_id", type="integer", example=null, nullable=true),
     * @OA\Property(property="car_id", type="integer", example=null, nullable=true),
     * @OA\Property(property="origin", type="string", example="Душанбе, ул. Рудаки 15"),
     * @OA\Property(property="destination", type="string", example="Худжанд, 19-й микрорайон"),
     * @OA\Property(property="departure_time", type="string", example="2026-06-15 14:00:00"),
     * @OA\Property(property="preferences", type="string", example="С кондиционером, без курящих", nullable=true),
     * @OA\Property(property="status", type="string", example="cancelled"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2026-06-12 15:00:00"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2026-06-12 15:10:00")
     * )
     * )
     * ),
     * @OA\Response(response=400, description="Невозможно отменить поездку (например, она уже в пути или завершена)"),
     * @OA\Response(response=401, description="Неавторизован"),
     * @OA\Response(response=404, description="Поездка не найдена")
     * )
     */
    public function cancel() {}

    /**
     * @OA\Get(
     * path="/driver/trips/available",
     * operationId="getAvailableTrips",
     * tags={"Trips - Driver"},
     * summary="Водитель: Просмотр доступных заявок на поездки",
     * description="Возвращает список всех свободных заявок на поездки системы со статусом PENDING.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="per_page",
     * in="query",
     * description="Количество элементов на странице",
     * required=false,
     * @OA\Schema(type="integer", example=10)
     * ),
     * @OA\Response(
     * response=200,
     * description="Список свободных заявок успешно получен",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=2),
     * @OA\Property(property="passenger_id", type="integer", example=8),
     * @OA\Property(property="driver_id", type="integer", example=null, nullable=true),
     * @OA\Property(property="car_id", type="integer", example=null, nullable=true),
     * @OA\Property(property="origin", type="string", example="Канибадам, Центр"),
     * @OA\Property(property="destination", type="string", example="Худжанд, Шёлкокомбинат"),
     * @OA\Property(property="departure_time", type="string", example="2026-06-16 08:00:00"),
     * @OA\Property(property="preferences", type="string", example="Сзади пустой багажник для сумок", nullable=true),
     * @OA\Property(property="status", type="string", example="pending")
     * )
     * )
     * )
     * ),
     * @OA\Response(response=401, description="Неавторизован")
     * )
     */
    public function available() {}

    /**
     * @OA\Post(
     * path="/trips/{id}/accept",
     * operationId="acceptTrip",
     * tags={"Trips - Driver"},
     * summary="Водитель: Принять поездку",
     * description="Привязывает водителя (driver_id) и его автомобиль (car_id) к поездке. Статус меняется на IN_PROGRESS.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID поездки",
     * required=true,
     * @OA\Schema(type="integer", example=2)
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"car_id"},
     * @OA\Property(property="car_id", type="integer", example=1, description="ID автомобиля водителя из таблицы cars")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Вы успешно приняли заказ",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Вы успешно приняли заказ"),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=2),
     * @OA\Property(property="passenger_id", type="integer", example=8),
     * @OA\Property(property="driver_id", type="integer", example=3, description="ID текущего авторизованного водителя"),
     * @OA\Property(property="car_id", type="integer", example=1, description="ID выбранного автомобиля из запроса"),
     * @OA\Property(property="origin", type="string", example="Канибадам, Центр"),
     * @OA\Property(property="destination", type="string", example="Худжанд, Шёлкокомбинат"),
     * @OA\Property(property="departure_time", type="string", example="2026-06-16 08:00:00"),
     * @OA\Property(property="status", type="string", example="in_progress", description="Статус меняется на in_progress")
     * )
     * )
     * ),
     * @OA\Response(response=401, description="Неавторизован"),
     * @OA\Response(response=422, description="Ошибка валидации (car_id не существует в таблице cars)"),
     * @OA\Response(response=404, description="Поездка не найдена")
     * )
     */
    public function accept() {}

    /**
     * @OA\Post(
     * path="/trips/{id}/complete",
     * operationId="completeTrip",
     * tags={"Trips - Driver"},
     * summary="Водитель: Завершить поездку",
     * description="Переводит статус поездки в COMPLETED по завершении маршрута.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID поездки",
     * required=true,
     * @OA\Schema(type="integer", example=2)
     * ),
     * @OA\Response(
     * response=200,
     * description="Поездка успешно завершена",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Поездка успешно завершена"),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=2),
     * @OA\Property(property="status", type="string", example="completed")
     * )
     * )
     * ),
     * @OA\Response(response=400, description="Невозможно завершить поездку (например, она не была принята или уже отменена)"),
     * @OA\Response(response=401, description="Неавторизован"),
     * @OA\Response(response=404, description="Поездка не найдена")
     * )
     */
    public function complete() {}
}