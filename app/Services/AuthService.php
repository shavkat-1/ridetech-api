<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;    
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function register(array $data): array
    {
        // Хешируем пароль перед записью в базу (безопасность превыше всего)
        $data['password'] = Hash::make($data['password']);

        // Создаем пользователя через абстракцию репозитория
        /** @var User $user */
        $user = $this->userRepository->create($data);

        // Генерируем токен Sanctum для авторизации API-запросов
        $token = $user->createToken('ridetech_auth_token')->plainTextToken;

        // Возвращаем пользователя и его токен
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /** 
     * Бизнес-логика входа (аутентификации) пользователя
     * @throws ValidationException
     */


    public function login(array $data): array 
    {
        // Ищем пользователя по email через репозиторий
        $user = $this->userRepository->findByEmail($data['email']);

        // Берем пароль из массива $data['password']
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверный email или пароль.'],
            ]);
        }

        // Генерируем новый токен Sanctum для авторизации API-запросов
        $token = $user->createToken('ridetech_auth_token')->plainTextToken;

        // Возвращаем пользователя и его токен
        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}

