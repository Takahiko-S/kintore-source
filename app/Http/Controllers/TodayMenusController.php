<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodayMenusRequest;
use App\Models\Exercises;
use App\Models\History;
use App\Models\Menu;
use App\Models\MenuExercise;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodayMenusController extends Controller

{

    //-------------------------------------------------------------------------------------------------------------------------------------------



    public function todayMenu()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        // Find the next menu
        $menu = Menu::where('user_id', $user_id)
            ->where('order', '>', $user->last_finish_order)
            ->orderBy('order', 'asc')
            ->first();

        if (!$menu) {
            // If there is no next menu, display the first menu
            $menu = Menu::where('user_id', $user_id)
                ->orderBy('order', 'asc')
                ->first();
        }



        return view('contents.today_menu', compact('menu'));
    }








    public function completeMenu(Request $request, $id)
    {
        $completed_exercises = $request->input('completed_exercises');
        if (!$completed_exercises) {
            return redirect()->route('today_menu', ['id' => $id])->with('error', '完了が選択されていません');
        }

        $menu = Menu::find($id);

        foreach ($completed_exercises as $completed_exercise) {
            $menuExercise = MenuExercise::find($completed_exercise);

            if (is_null($menuExercise->reps)) {
                return redirect()->route('today_menu', ['id' => $id])->with('error', '回数の情報がありません。');
            }

            if (is_null($menuExercise->weight)) {
                return redirect()->route('today_menu', ['id' => $id])->with('error', '重量の情報がありません。');
            }

            History::create([
                'user_id' => Auth::id(),
                'menu_id' => $menu->id,
                'exercise_id' => $menuExercise->exercise_id,
                'menu_exercise_id' => $completed_exercise,
                'exercise_date' => Carbon::now(),
                'menu_name' => $menu->name,
                'exercise_name' => $menuExercise->exercise->name,
                'sets' => $menuExercise->set,
                'weight' => $menuExercise->weight,
                'reps' => $menuExercise->reps,
                'memo' => '',
            ]);
        }

        return redirect()->route('today_menu', ['id' => $id]);
    }


    public function todayComplete($id)
    {
        // Update the last completed menu for the user
        $user = Auth::user();
        $menu = Menu::find($id);
        $user->last_completed_menu_id = $id;
        $user->last_finish_order = $menu->order;
        $user->save();

        // Find the next menu
        $nextMenu = Menu::where('user_id', Auth::id())
            ->where('order', '>', $menu->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextMenu) {
            // If there is a next menu, redirect to it
            return redirect()->route('today_menu', ['id' => $nextMenu->id]);
        } else {
            // If there is no next menu, redirect to the first menu
            $firstMenu = Menu::where('user_id', Auth::id())
                ->orderBy('order', 'asc')
                ->first();
            return redirect()->route('today_menu', ['id' => $firstMenu->id]);
        }
    }







    //-------------------------------------------------------------------------------------------------------------------------------------------
    public function todayEdit(string $id)
    {

        $menu = Menu::find($id);
        $exercises = Exercises::all()->groupBy('body_part');

        return view('contents.today_edit', compact('menu', 'exercises'));
    }


    public function todayUpdate(TodayMenusRequest $request, string $id)
    {

        $menu = Menu::findOrFail($id);
        $menu->name = $request->name;

        // 送信された全てのメニューエクササイズのIDを配列に格納
        $menuExerciseIds = array_filter(
            array_column($request->menu_exercises, 'id'),
            function ($id) {
                return $id !== 'new';
            }
        );

        // データベースから現在のメニューに関連する全てのメニューエクササイズを取得し、それらが上記の配列に存在しない場合、それらを削除
        MenuExercise::where('menu_id', $id)->whereNotIn('id', $menuExerciseIds)->delete();

        // 以下は既存のコードを維持
        foreach ($request->menu_exercises as $menuExerciseData) {
            if (isset($menuExerciseData['id']) && $menuExerciseData['id'] != 'new') {
                // Existing menu exercise update
                $menuExercise = MenuExercise::find($menuExerciseData['id']);
                if ($menuExercise !== null) {
                    $menuExercise->reps = $menuExerciseData['reps'];
                    $menuExercise->weight = $menuExerciseData['weight'];
                    if (isset($menuExerciseData['memo'])) {
                        $menuExercise->memo = $menuExerciseData['memo'];
                    }
                    $menuExercise->save();
                }
            } else {
                // Add new menu exercise
                $newMenuExercise = new MenuExercise();
                $newMenuExercise->menu_id = $menu->id;
                $newMenuExercise->exercise_id = $menuExerciseData['exercise_id'];
                $newMenuExercise->set = $menuExerciseData['set'];
                $newMenuExercise->reps = $menuExerciseData['reps'];
                $newMenuExercise->weight = $menuExerciseData['weight'];
                if (isset($menuExerciseData['memo'])) {
                    $newMenuExercise->memo = $menuExerciseData['memo'];
                }
                $newMenuExercise->save();
            }
        }

        $menu->save();
        session()->flash('message', 'メニューが更新されました');
        return redirect()->route('today_menu');
    }


    //ーーーーーーーーーーーーーーーーーーーーーーー削除ーーーーーーーーーーーーーーーーーーーーーーーー
    public function todayDestroy(string $id)
    {
        // 送られてきた id から対応する MenuExercise を取得
        $menuExercise = MenuExercise::find($id);

        // 取得した MenuExercise の menu_id と exercise_id を取得
        $menu_id = $menuExercise->menu_id;
        $exercise_id = $menuExercise->exercise_id;

        // 同じ menu_id を持ち、同じ exercise_id を持つすべての MenuExercise を削除
        MenuExercise::where('menu_id', $menu_id)
            ->where('exercise_id', $exercise_id)
            ->delete();

        return response()->json(['menu_id' => $menu_id]);  // JSONレスポンスを返す
    }

    //ーーーーーーーーーーーーーーーーーーーーーーー種目追加ーーーーーーーーーーーーーーーーーーーーーーーー
    public function addExercises(TodayMenusRequest $request) // 種目追加モーダルからの送信
    {
        $menuId = $request->input('menu_id');
        $menu = Menu::find($menuId);
        $existingExerciseIds = $menu->exercises->pluck('id')->toArray();

        $validator = Validator::make($request->all(), [
            'selectedExercises' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) use ($existingExerciseIds) {
                    if (array_intersect($value, $existingExerciseIds)) {
                        $fail('既にメニューに登録されている種目が選択されています。');
                    }
                },
            ],
            // その他のバリデーションルール
        ]);

        if ($validator->fails()) {
            session()->flash('showModal', true);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get the request data
        $exerciseIds = $request->input('selectedExercises');
        $currentExercisesCount = $menu->exercises()->count();

        // Save the data
        foreach ($exerciseIds as $exerciseId) {
            $menu->exercises()->attach($exerciseId, ['set' => 1, 'index' => $currentExercisesCount]);
            $currentExercisesCount++; // Increment the index for the next exercise
        }

        // Redirect to the menu detail page
        return redirect()->route('today_edit', ['id' => $menu->id]);
    }
}
