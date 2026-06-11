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
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Группируем роуты версии V1
Route::prefix('v1')->group(function () {
    
    // Публичные роуты аутентификации
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    // Защищенные роуты (доступны только по Sanctum токену)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    
        // Роуты управления машинами для водителя
        Route::post('/cars', [CarController::class, 'store']);     // Добавить машину
        Route::get('/cars', [CarController::class, 'index']);      // Получить свои машины
        Route::put('/cars/{carId}', [CarController::class, 'update']); // Обновить машину
        Route::delete('/cars/{carId}', [CarController::class, 'destroy']); // Удалить машину

        // Роуты поездок для пассажира
        Route::post('/trips', [TripController::class, 'store']);          // Создать поездку
        Route::post('/trips/{id}/cancel', [TripController::class, 'cancel']); // Отменить поездку
        Route::get('/trips', [TripController::class, 'index']);           // История поездок пассажира


        // Роуты поездок для водителя
        Route::get('/driver/trips/available', [TripController::class, 'available']); // Список свободных заказов
        Route::post('/trips/{id}/accept', [TripController::class, 'accept']);        // Принять заказ
        Route::post('/trips/{id}/complete', [TripController::class, 'complete']);    // Завершить заказ


        //Отзывы
        Route::post('/trips/{tripId}/reviews', [ReviewController::class, 'store']); // Оставить отзыв после поездки
        Route::get('/reviews/{driverId}', [ReviewController::class, 'index']); // Получить список отзывов конкретного водителя

});
});

