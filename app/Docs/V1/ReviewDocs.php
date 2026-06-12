<?php

namespace App\Docs\V1;

class ReviewDocs
{
    /**
     * @OA\Get(
     * path="/trips/{trip}/reviews",
     * operationId="getTripReviews",
     * tags={"Reviews"},
     * summary="Получить отзывы к поездке",
     * description="Возвращает все отзывы, оставленные в рамках конкретной поездки.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="trip",
     * in="path",
     * description="ID поездки",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
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
     * @OA\Property(property="trip_id", type="integer", example=1),
     * @OA\Property(property="user_id", type="integer", example=5, description="ID автора отзыва"),
     * @OA\Property(property="rating", type="integer", example=5),
     * @OA\Property(property="comment", type="string", example="Всё супер, доехали быстро")
     * )
     * )
     * )
     * ),
     * @OA\Response(response=401, description="Неавторизован"),
     * @OA\Response(response=404, description="Поездка не найдена")
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     * path="/trips/{trip}/reviews",
     * operationId="reviewTrip",
     * tags={"Reviews"},
     * summary="Оставить отзыв о поездке",
     * description="Позволяет пассажиру или водителю выставить оценку от 1 до 5 и написать комментарий по завершении поездки.",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="trip",
     * in="path",
     * description="ID поездки",
     * required=true,
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"rating", "comment"},
     * @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5, description="Оценка от 1 до 5"),
     * @OA\Property(property="comment", type="string", example="Отличная поездка, водитель пунктуальный!")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Отзыв успешно добавлен",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="trip_id", type="integer", example=1),
     * @OA\Property(property="rating", type="integer", example=5),
     * @OA\Property(property="comment", type="string", example="Отличная поездка, водитель пунктуальный!")
     * )
     * )
     * ),
     * @OA\Response(response=400, description="Нельзя оставить отзыв на незавершенную поездку"),
     * @OA\Response(response=401, description="Неавторизован"),
     * @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store() {}
}