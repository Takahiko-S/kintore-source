<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'menu_id',
        'exercise_id',
        'menu_exercise_id',
        'exercise_date',
        'menu_name',
        'exercise_name',
        'sets',
        'weight',
        'reps',
        'memo',

    ];
}