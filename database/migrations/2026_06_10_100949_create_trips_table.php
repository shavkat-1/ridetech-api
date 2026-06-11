<?php

use App\Enums\TripStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->constrained('users')->onDelete('cascade'); // Внешний ключ на таблицу users (пассажир)   

            // Водитель может быть назначен позже, поэтому делаем его nullable
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null'); // Внешний ключ на таблицу users (водитель)
            $table->foreignId('car_id')->nullable()->constrained('cars')->onDelete('set null'); // Внешний ключ на таблицу cars


            $table->string('origin'); // Место отправления
            $table->string('destination'); // Место назначения
            $table->timestamp('departure_time'); // Время отправления
            $table->text('preferences')->nullable(); // Предпочтения пассажира

            // Статус поездки (запланирована, в пути, завершена, отменена)
            $table->string('status')->default(TripStatus::PENDING->value);

            $table->timestamps();


            // Индексы для ускорения поиска поездок по пассажиру, водителю и статусу
            $table->index('passenger_id');
            $table->index('driver_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
