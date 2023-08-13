<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TodayMenusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HistoryController::class, 'top'])->name('history.top');

Route::middleware('auth')->group(function () {
    Route::get('/index', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/show_history{date}', [HistoryController::class, 'showHistory'])->name('show_history');

    Route::get('/today_menu', [TodayMenusController::class, 'todayMenu'])->name('today_menu');
    Route::get('/today_edit/{id}', [TodayMenusController::class, 'todayEdit'])->name('today_edit');
    Route::patch('/today_update/{id}', [TodayMenusController::class, 'todayUpdate'])->name('today_update');
    Route::post('/add_exercise', [TodayMenusController::class, 'addExercises'])->name('add_exercise');
    Route::delete('/today_destroy/{id}', [TodayMenusController::class, 'todayDestroy'])->name('today_destroy');
    //Route::delete('/today_destroy/{id}', [TodayMenuController::class, 'MenuExercisedestroy'])->name('today_destroy');
    Route::post('/complete_menu/{id}', [TodayMenusController::class, 'completeMenu'])->name('complete_menu');
    Route::post('/today_complete/{id}', [TodayMenusController::class, 'todayComplete'])->name('today_complete');

    Route::get('/schedule', [ScheduleController::class, 'schedule_index'])->name('schedule_index');
    Route::get('/schedule/{id}/edit', [ScheduleController::class, 'schedule_edit'])->name('schedule.edit');
    Route::post('/schedule_update/{id}', [ScheduleController::class, 'scheduleUpdate'])->name('schedule_update');
    Route::post('/menu_delete', [ScheduleController::class, 'menuDelete'])->name('menu_delete');
    Route::get('/new_schedule', [ScheduleController::class, 'newSchedule'])->name('new_schedule');
    Route::post('/add_menu', [ScheduleController::class, 'addMenu'])->name('add_menu');
    Route::get('/add_new_exercise', [ScheduleController::class, 'addNewExercise'])->name('add_new_exercise');
    Route::post('/add_new_exercise', [ScheduleController::class, 'addNewExercise'])->name('add_new_exercise');
    Route::post('/schedule_add_exercise', [ScheduleController::class, 'scheduleAddExercise'])->name('schedule_add_exercise');
    Route::get('/get_new_exercise', [ScheduleController::class, 'getNewExercises'])->name('get_new_exercise');
    Route::post('/update_menu_order', [ScheduleController::class, 'updateMenuOrder'])->name('update_menu_order');
});

//Route::get('/dashboard', function () {return view('dashboard');})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
