<?php

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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            // Внешний ключ на таблицу users
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->string('brand'); // Марка автомобиля
            $table->string('model'); // Модель автомобиля
            $table->unsignedInteger('year'); // Год выпуска
            $table->string('license_plate')->unique(); // Номерной знак
            $table->timestamps();

            $table->index('driver_id'); // Индекс для ускорения поиска автомобилей по водителю
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
