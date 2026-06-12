<?php

namespace App\Docs\V1;

class CarDocs
{
    /**
     * @OA\Get(
     * path="/cars",
     * operationId="getDriverCars",
     * tags={"Cars"},
     * summary="Получить список автомобилей текущего водителя",
     * description="Возвращает массив всех автомобилей, которые принадлежат текущему авторизованному водителю.",
     * security={{"bearerAuth": {}}},
     * @OA\Response(
     * response=200,
     * description="Успешный запрос",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="driver_id", type="integer", example=3),
     * @OA\Property(property="brand", type="string", example="Hyundai"),
     * @OA\Property(property="model", type="string", example="Sonata"),
     * @OA\Property(property="year", type="integer", example=2021),
     * @OA\Property(property="license_plate", type="string", example="0001PT02"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2026-06-12 12:00:00"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2026-06-12 12:00:00")
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
     * path="/cars",
     * operationId="addCar",
     * tags={"Cars"},
     * summary="Добавить автомобиль",
     * description="Позволяет авторизованному водителю добавить свой автомобиль в систему. Номерной знак должен быть уникальным.",
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"brand", "model", "year", "license_plate"},
     * @OA\Property(property="brand", type="string", example="Hyundai", description="Марка автомобиля"),
     * @OA\Property(property="model", type="string", example="Sonata", description="Модель автомобиля"),
     * @OA\Property(property="year", type="integer", example=2021, description="Год выпуска"),
     * @OA\Property(property="license_plate", type="string", example="0001PT02", description="Уникальный номерной знак")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Автомобиль успешно добавлен",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Автомобиль успешно добавлен"),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="driver_id", type="integer", example=3),
     * @OA\Property(property="brand", type="string", example="Hyundai"),
     * @OA\Property(property="model", type="string", example="Sonata"),
     * @OA\Property(property="year", type="integer", example=2021),
     * @OA\Property(property="license_plate", type="string", example="0001PT02"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2026-06-12 15:00:00"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2026-06-12 15:00:00")
     * )
     * )
     * ),
     * @OA\Response(response=401, description="Неавторизован"),
     * @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store() {}

    /**
     * @OA\Put(
     * path="/cars/{carId}",
     * operationId="updateCar",
     * tags={"Cars"},
     * summary="Обновить информацию об автомобиле",
     * description="Позволяет водителю обновить марку, модель, год выпуска или номерной знак своего автомобиля по его ID.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="carId",
     * in="path",
     * description="ID автомобиля в базе данных",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"brand", "model", "year", "license_plate"},
     * @OA\Property(property="brand", type="string", example="Hyundai"),
     * @OA\Property(property="model", type="string", example="Sonata (Рестайлинг)"),
     * @OA\Property(property="year", type="integer", example=2022),
     * @OA\Property(property="license_plate", type="string", example="0001PT02")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Информация об автомобиле успешно обновлена",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Информация об автомобиле успешно обновлена"),
     * @OA\Property(property="data", type="object", description="Обновленные данные машины")
     * )
     * ),
     * @OA\Response(response=401, description="Неавторизован"),
     * @OA\Response(response=404, description="Автомобиль не найден у этого водителя"),
     * @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     * path="/cars/{carId}",
     * operationId="deleteCar",
     * tags={"Cars"},
     * summary="Удалить автомобиль",
     * description="Удаляет выбранный автомобиль текущего водителя из системы.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="carId",
     * in="path",
     * description="ID автомобиля, который нужно удалить",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Автомобиль успешно удален",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Автомобиль успешно удален")
     * )
     * ),
     * @OA\Response(response=401, description="Неавторизован"),
     * @OA\Response(response=404, description="Автомобиль не найден")
     * )
     */
    public function destroy() {}
}