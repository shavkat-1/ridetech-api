<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'reviewer_id',
        'reviewee_id',
        'rating',
        'comment',
    ];


     protected $casts = [
        'rating' => 'integer',
    ];

    // Отзыв относится к одной поездке
    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }


    // Рецензент (тот, кто оставляет отзыв)
     public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }


    // Рецензируемый (тот, кому оставляют отзыв)
    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    
}
