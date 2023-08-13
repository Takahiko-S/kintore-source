<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class TestController extends Controller
{
    public function todayUpdate(Request $request, string $id)
    {


        $menu = Menu::find($id);
        $menu->name = $request->name;
        foreach ($request->menu_exercises as $index => $menuExerciseData) {
        }
    }
}
