<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Exercises; // Exerciseモデルのネームスペースを確認してください。

class ExercisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $init_exercises = [
            ['name' => 'ベンチプレス', 'body_part' => '胸'],
            ['name' => 'ダンベルフライ', 'body_part' => '胸'],
            ['name' => 'インクラインベンチプレス', 'body_part' => '胸'], // 追加

            ['name' => 'バーベルカール', 'body_part' => '腕'],
            ['name' => 'インクラインアームカール', 'body_part' => '腕'],
            ['name' => 'インクラインハンマーカール', 'body_part' => '腕'], // 追加

            ['name' => 'デッドリフト', 'body_part' => '背中'],
            ['name' => 'ラットプルダウン', 'body_part' => '背中'], // 追加
            ['name' => 'チンニング', 'body_part' => '背中'],

            ['name' => 'スクワット', 'body_part' => '脚'],
            ['name' => 'レッグエクステンション', 'body_part' => '脚'], // 追加
            ['name' => 'レッグカール', 'body_part' => '脚'], // 追加
            ['name' => 'レッグプレス', 'body_part' => '脚'],
            ['name' => 'ブルガリンスクワット', 'body_part' => '脚'], // 追加

            ['name' => 'キューバンプレス', 'body_part' => '肩'],
            ['name' => 'サイドレイズ', 'body_part' => '肩'],
            ['name' => 'ダンベルレイズ', 'body_part' => '肩'], // 追加
            ['name' => 'ショルダープレス', 'body_part' => '肩'],
        ];

        foreach ($init_exercises as $exercise) {
            $data = new Exercises();
            $data->name = $exercise['name'];
            $data->body_part = $exercise['body_part'];
            $data->save();
        }
    }
}
