<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuExercise extends Model
{

    public function exercise()
    {
        return $this->belongsTo(Exercises::class, 'exercise_id');
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }
}
