<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) { //$iが設定されている場所の数字が、$iが1から3までの間でループする
            DB::table('histories')->insert([
                'user_id' => 1,
                'menu_id' => 1,
                'menu_exercise_id' => $i,
                'menu_name' => '月曜',
                'exercise_name' => 'ベンチプレス',
                'exercise_date' => Carbon::now()->subDays(2),
                'exercise_id' => 1,
                'sets' => $i, // Here, $i will be 1, then 2, then 3
                'reps' => 5,
                'weight' => 80,
                'memo' => 'ここにメモを記入する',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ]);
        };
        for ($i = 1; $i <= 2; $i++) { //$iが設定されている場所の数字が、$iが1から3までの間でループする
            DB::table('histories')->insert([
                'user_id' => 1,
                'menu_id' => 1,
                'menu_exercise_id' => $i + 3,
                'menu_name' => '月曜',
                'exercise_name' => 'ダンベルフライ',
                'exercise_date' => Carbon::now()->subDays(2),
                'exercise_id' => 2,
                'sets' => $i, // Here, $i will be 1, then 2, then 3
                'reps' => 8,
                'weight' => 60,
                'memo' => 'ここにメモを記入する',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ]);
        };
        for ($i = 1; $i <= 2; $i++) { //$iが設定されている場所の数字が、$iが1から3までの間でループする
            DB::table('histories')->insert([
                'user_id' => 1,
                'menu_id' => 1,
                'menu_exercise_id' => $i + 5,
                'menu_name' => '月曜',
                'exercise_name' => 'インクラインベンチプレス',
                'exercise_date' => Carbon::now()->subDays(2),
                'exercise_id' => 3,
                'sets' => $i, // Here, $i will be 1, then 2, then 3
                'reps' => 10,
                'weight' => 50,
                'memo' => 'ここにメモを記入する',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ]);
        };
    }
}
