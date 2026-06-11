<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Requests\Api\V1\Auth\LoginRequest; // Импортируем LoginRequest
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Внедряем AuthService один раз через конструктор класса
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Эндпоинт регистрации пользователя
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // Теперь $this->authService доступен во всем классе
        $result = $this->authService->register($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Пользователь успешно зарегистрирован',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token'],
            ]
        ], 201);
    }

    /**
     * Эндпоинт входа в систему
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Передаем валидированные данные в сервис
        $result = $this->authService->login($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Успешный вход в систему',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token'],
            ]
        ], 200);
    }

    /**
     * Эндпоинт выхода из системы
     */
    public function logout(Request $request): JsonResponse
    {
        // Отзываем текущий токен Sanctum
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Успешный выход из системы (токен отозван)'
        ], 200);
    }
}