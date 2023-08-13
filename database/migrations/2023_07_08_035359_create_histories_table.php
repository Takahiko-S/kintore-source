<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('exercise_id');
            $table->unsignedBigInteger('menu_exercise_id');
            $table->date('exercise_date');
            $table->string('menu_name')->comment('メニュー名');
            $table->string('exercise_name')->comment('種目名');
            $table->integer('sets')->comment('セット数');
            $table->integer('weight')->comment('重量');
            $table->integer('reps')->comment('回数');
            $table->string('memo')->comment('メモ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};