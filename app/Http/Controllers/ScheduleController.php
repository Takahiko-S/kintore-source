<?php

namespace App\Http\Controllers;

use App\Http\Requests\S_UpdateRequest;
use App\Http\Requests\ScheduleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\Exercises;
use App\Models\MenuExercise;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function schedule_index()
    {
        $menus = Menu::where('user_id', Auth::id())->orderBy('order')->get();
        $menuExists = Menu::where('user_id', Auth::id())->count() > 0;
        $exercises = Exercises::all()->groupBy('body_part');
        $body_parts = Exercises::select('body_part')->distinct()->get()->pluck('body_part');

        //dd($menus);
        return view('contents.schedule', compact('menus', 'exercises', 'body_parts', 'menuExists'));
    }

    //--------------------------------------------//セット追加などの更新---------------------------------------
    public function scheduleUpdate(S_UpdateRequest $request, string $id)
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
        return redirect()->route('schedule_index');
    }

    //--------------------------------------------//編集画面遷移---------------------------------------
    public function schedule_Edit(string $id)
    {
        //
        $menu = Menu::find($id);;
        $exercises = Exercises::all()->groupBy('body_part');

        return view('contents.schedule_edit', compact('menu', 'exercises'));
    }

    //--------------------------------------------メニュー削除ーーーーーーーーーー---------------------------------------
    public function menuDelete(Request $request)
    {

        Menu::destroy($request->menuId);
        // メニューが全て削除されたかを確認
        if (Menu::count() == 0) {
            // フラッシュメッセージの保存
            session()->flash('message', '全てのメニューが削除されました');
        }

        return response()->json(['status' => 'success']);
    }
    //-------------------------------------------------------メニュー追加-------------------------------------------------------------------------
    public function addMenu(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'selectedExercises' => 'required|array|min:1',
        ], [], [
            'selectedExercises' => '選択されたエクササイズ',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('showModal', 'true');
        }
        $menu = new Menu(); // 新しいMenuインスタンスを作成します

        $menu->user_id = Auth::id();  // ユーザーIDを認証済みユーザーのIDで設定します
        $menu->name = $request->menu_name;        // メニューの名前をリクエストから取得します

        // 新しいメニューを挿入する位置を取得 もしリクエストに位置が指定されていなければ、ユーザーの既存のメニューの数+1を使用します
        $insertPosition = $request->insert_position ? intval($request->insert_position) + 1 : Menu::where('user_id', Auth::id())->count() + 1;

        $menusToUpdate = Menu::where('user_id', Auth::id()) // 挿入位置以降のすべてのメニューを取得します
            ->where('order', '>=', $insertPosition)
            ->get();
        foreach ($menusToUpdate as $menuToUpdate) {     // 影響を受けるメニューの順序を更新します
            $menuToUpdate->order++;
            $menuToUpdate->save();
        }

        $menu->order = $insertPosition; // 新しいメニューの順序を設定し、それを保存します
        $menu->save();

        if ($request->has('selectedExercises')) {        //menu_exerciseテーブルにデータを保存
            foreach ($request->selectedExercises as $exerciseId) {
                $menu_exercise = new MenuExercise();
                $menu_exercise->menu_id = $menu->id;
                $menu_exercise->exercise_id = $exerciseId;
                $menu_exercise->set = 1;
                $menu_exercise->save();
            }
        }


        return redirect()->route('schedule_index', ['id' => $menu->id]);        // メニュー詳細ページにリダイレクトします
    }


    //-------------------------------------------------------筋トレ種目追加-------------------------------------------------------------------------
    public function scheduleAddExercise(Request $request) //スケジュール編集画面の種目追加
    {
        $validator = Validator::make($request->all(), [
            'selectedExercises' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('showModal', 'true');
        }
        // Get the request data
        $exerciseIds = $request->input('selectedExercises');
        $menuId = $request->input('menu_id');

        // Retrieve the specific Menu
        $menu = Menu::find($menuId);
        $currentExercisesCount = $menu->exercises()->count();
        // Save the data
        foreach ($exerciseIds as $exerciseId) {
            $menu->exercises()->attach($exerciseId, ['set' => 1, 'index' => $currentExercisesCount]);
            $currentExercisesCount++; // Increment the index for the next exercise
        }

        // Redirect to the menu detail page
        return redirect()->route('schedule.edit', ['id' => $menu->id]);
    }
    //-------------------------------------------------------新規種目追加-------------------------------------------------------------------------
    public function addNewExercise(ScheduleRequest $request)

    {

        // すでに存在する名前かどうかをチェック
        $existingExercise = Exercises::where('name', $request->exercise_name)->first();
        if ($existingExercise) {
            return response()->json([
                'status' => 'error',
                'message' => 'この名前の種目はすでに登録されています。'
            ], 400); // 400 Bad Requestを返す
        }
        //exerciseテーブルにデータを保存
        $exercise = new Exercises();
        $exercise->name = $request->exercise_name;

        // ユーザーが新しい部位を追加した場合、それを使用
        if (!empty($request->new_body_part)) {
            $exercise->body_part = $request->new_body_part;

            // 新しい部位を部位テーブルに保存
        } else {
            // そうでなければ、選択された既存の部位を使用
            $exercise->body_part = $request->body_part;
        }

        $exercise->save();

        return response()->json([
            'status' => 'success',
            'message' => 'New exercise added successfully.'
        ]);
    }



    public function getNewExercises() //モーダルで更新された新しいエクササイズを取得
    {
        // Exercisesテーブルからすべてのエクササイズを取得
        $exercises = Exercises::all();

        // エクササイズのリストをJSON形式で返す
        return response()->json($exercises);
    }
    //---------------------------ーーーーーー------メニュー並び替え----------------ーーー------------------------
    public function updateMenuOrder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $id => $newOrder) {
            $menu = Menu::find($id);
            if ($menu) {
                $menu->order = $newOrder;
                $menu->save();
            }
        }

        return response()->json(['status' => 'success']);
    }
}
