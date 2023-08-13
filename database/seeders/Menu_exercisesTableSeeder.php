<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuExercise;

class Menu_exercisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // メニューと種目の関連データを作成
        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 1,
            'set' => 1,
            'reps' => 5,
            'weight' => 80,
            'index' => 1,
        ]);
        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 1,
            'set' => 2,
            'reps' => 5,
            'weight' => 80,
            'index' => 2,
        ]);
        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 1,
            'set' => 3,
            'reps' => 5,
            'weight' => 80,
            'index' => 3,
        ]);


        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 2,
            'set' => 1,
            'reps' => 8,
            'weight' => 60,
            'index' => 1,
        ]);
        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 2,
            'set' => 2,
            'reps' => 8,
            'weight' => 60,
            'index' => 2,
        ]);


        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 3,
            'set' => 1,
            'reps' => 10,
            'weight' => 50,
            'index' => 1,
        ]);
        MenuExercise::create([
            'menu_id' => 1,
            'exercise_id' => 3,
            'set' => 2,
            'reps' => 10,
            'weight' => 50,
            'index' => 2,
        ]);


        // menu_idが2のデータを作成
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 7,
            'set' => 1,
            'weight' => 50,
            'reps' => 10,
            'index' => 2,
        ]);
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 7,
            'set' => 2,
            'weight' => 50,
            'reps' => 10,
            'index' => 2,
        ]);
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 7,
            'set' => 3,
            'weight' => 50,
            'reps' => 10,
            'index' => 3,
        ]);
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 8,
            'set' => 1,
            'weight' => 50,
            'reps' => 10,
            'index' => 1,
        ]);
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 8,
            'set' => 2,
            'weight' => 50,
            'reps' => 10,
            'index' => 2,
        ]);
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 8,
            'set' => 3,
            'weight' => 50,
            'reps' => 10,
            'index' => 3,
        ]);

        // menu_idが3のデータを作成
        MenuExercise::create([
            'menu_id' => 3,
            'exercise_id' => 10,
            'set' => 1,
            'weight' => 60,
            'reps' => 10,
            'index' => 1,
        ]);
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 10,
            'set' => 2,
            'weight' => 50,
            'reps' => 10,
            'index' => 2,
        ]);

        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 10,
            'set' => 3,
            'weight' => 60,
            'reps' => 10,
            'index' => 3,
        ]);
        MenuExercise::create([
            'menu_id' => 3,
            'exercise_id' => 11,
            'set' => 1,
            'weight' => 60,
            'reps' => 10,
            'index' => 1,
        ]);
        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 11,
            'set' => 2,
            'weight' => 50,
            'reps' => 10,
            'index' => 2,
        ]);

        MenuExercise::create([
            'menu_id' => 2,
            'exercise_id' => 11,
            'set' => 3,
            'weight' => 60,
            'reps' => 10,
            'index' => 3,
        ]);


        // 追加の関連データをここに追記

    }
}
