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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade'); // Внешний ключ на таблицу trips
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade'); // Внешний ключ на таблицу users (тот, кто оставляет отзыв)
            $table->foreignId('reviewee_id')->constrained('users')->onDelete('cascade'); // Внешний ключ на таблицу users (тот, кому оставляют отзыв)
            $table->unsignedTinyInteger('rating'); // Рейтинг от 1 до 5
            $table->text('comment')->nullable(); // Комментарий к отзыву    
            $table->timestamps();

            // Индексы для ускорения поиска отзывов по поездке, рецензенту и рецензируемому
            $table->index('trip_id');
            $table->index('reviewer_id');
            $table->index('reviewee_id');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
