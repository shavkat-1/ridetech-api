<?php

namespace App\Docs\V1;

class AuthDocs
{
    /**
     * @OA\Post(
     * path="/auth/register",
     * summary="Регистрация пользователя",
     * description="Позволяет новому пользователю зарегистрироваться в системе.",
     * tags={"Auth"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "email", "password"},
     * @OA\Property(property="name", type="string", example="Иван Иванов"),
     * @OA\Property(property="email", type="string", format="email", example="ivan@example.com"),
     * @OA\Property(property="password", type="string", example="secret123")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Пользователь успешно зарегистрирован",
     * @OA\JsonContent(
     * example={
     * "success": true,
     * "message": "Пользователь успешно зарегистрирован",
     * "data": {
     * "id": 1,
     * "name": "Иван Иванов",
     * "email": "ivan@example.com"
     * }
     * }
     * )
     * ),
     * @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function register() {}

    /**
     * @OA\Post(
     * path="/auth/login",
     * summary="Авторизация пользователя",
     * description="Позволяет пользователю войти в систему и получить токен доступа.",
     * tags={"Auth"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"email","password"},
     * @OA\Property(property="email", type="string", format="email", example="ivan@example.com"),
     * @OA\Property(property="password", type="string", example="secret123")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Успешный вход в систему",
     * @OA\JsonContent(
     * example={
     * "success": true,
     * "message": "Успешный вход в систему",
     * "data": {
     * "token": "2|eyJ0eXAiOiJKV1QiLC..."
     * }
     * }
     * )
     * ),
     * @OA\Response(response=422, description="Неверные учетные данные")
     * )
     */
    public function login() {}

    /**
     * @OA\Post(
     * path="/auth/logout",
     * summary="Выход из системы",
     * description="Позволяет пользователю выйти из системы, деактивируя текущий токен доступа.",
     * tags={"Auth"},
     * security={{"bearerAuth": {}}},
     * @OA\Response(
     * response=200,
     * description="Успешный выход из системы",
     * @OA\JsonContent(
     * example={
     * "success": true,
     * "message": "Успешный выход из системы"
     * }
     * )
     * ),
     * @OA\Response(response=401, description="Неавторизован")
     * )
     */
    public function logout() {}
}