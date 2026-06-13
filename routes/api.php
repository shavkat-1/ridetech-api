<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Trip\TripController;
use App\Http\Controllers\Api\V1\Car\CarController;
use App\Http\Controllers\Api\V1\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Группируем все роуты под префиксом v1
Route::prefix('v1')->group(function () {
    
    // ----------------------------------------------------
    // Публичные роуты (Доступны без токена)
    // ----------------------------------------------------
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // ----------------------------------------------------
    // Защищенные роуты (Доступны только по Sanctum токену)
    // ----------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {

        // Сессия и профиль
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    
        // Управление машинами (Водитель)
        Route::post('/cars', [CarController::class, 'store']);       // Добавить
        Route::get('/cars', [CarController::class, 'index']);         // Список
        Route::put('/cars/{car}', [CarController::class, 'update']);  // Обновить
        Route::delete('/cars/{car}', [CarController::class, 'destroy']); // Удалить

        /// Поездки (Пассажир)
        Route::post('/trips', [TripController::class, 'store']);
        Route::get('/trips', [TripController::class, 'index']);
        Route::get('/trips/{trip}', [TripController::class, 'show']);
        Route::put('/trips/{trip}', [TripController::class, 'update']);
        Route::post('/trips/{trip}/cancel', [TripController::class, 'cancel']);

        // Поездки (Водитель)
        Route::get('driver/trips/available', [TripController::class, 'available']);
        Route::post('/trips/{trip}/accept', [TripController::class, 'accept']);
        Route::post('/trips/{trip}/complete', [TripController::class, 'complete']);

        Route::post('/trips/{trip}/reviews', [ReviewController::class, 'store']);
        Route::get('/trips/{trip}/reviews', [ReviewController::class, 'tripReviews']);
        Route::get('/reviews/{user}', [ReviewController::class, 'driverReviews']);
    });
});