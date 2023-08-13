<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;


class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // メニューのデータを作成
        $menus = [
            [
                'user_id' => 1,
                'name' => '月曜',
                'description' => '月曜日は胸トレメインです',
                'order' => 1,
            ],
            [
                'user_id' => 1,
                'name' => '火曜',
                'description' => '火曜日は背中トレメインです',
                'order' => 2,
            ],
            [
                'user_id' => 1,
                'name' => '水曜',
                'description' => '水曜日は脚トレメインです',
                'order' => 3,
            ],

            // 追加のメニューデータをここに追記
        ];

        // メニューデータをデータベースに挿入
        foreach ($menus as $menuData) {
            Menu::create($menuData);
        }
    }
}
