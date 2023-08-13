<x-base-layout>
    <!-- カレンダー表示 -->

    <x-slot name="title">ホーム</x-slot>
    <x-slot name="css">
        <style type="text/css">
            .small-input {
                width: 70%;
            }
        </style>
    </x-slot>

    <x-slot name="main">
        <div class="container">

            <h1 class="text-center mt-5">今日のメニュー編集</h1>


            <form action="" method="POST">
                @csrf
                @method('PATCH')

                <div class="row mt-2">
                    <!-- Button trigger modal -->
                    <div class="col-12 mt-3">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" id="modal_bt">
                                    種目追加
                                </button>
                            </div>

                            <div class="col-6 text-end">
                                <button type="submit" class="btn btn-primary">更新</button>
                            </div>
                        </div>

                    </div>

                </div>


	<div class="col-md-6 mt-3">
			@if ($menu->menuExercises->isEmpty())
			<div class="col-md-6 mt-3">
				<h4>メニュー名</h4>
				<input type="text" name="name" value="{{ $menu->name }}"
					class="form-control">
			</div>
			<h4 text-center>メニューが登録されていません。</h4>
			@else
			<h4>メニュー名</h4>
			<input type="text" name="name" value="{{ $menu->name }}" class="form-control">
		</div>

		<!-- メニュー編集部分 -->
                @foreach ($menu->menuExercises as $index => $menuExercise)
                    <div class="exercise-block" data-exercise-id="{{ $menuExercise->id }}">
                        <!-- 種目を囲むdiv -->
                        <h3 class="text-start mt-3">{{ $menuExercise->exercise->name }}</h3>

                        <div class="form-group">
                            <table class="table table-bordered">
                                <!-- ラベル部分 -->
                                <thead>
                                    <tr>
                                        <th class="col-1 col-md-1 text-center" style="font-size: 0.8em;">セット</th>
                                        <th class="col-1 col-md-1 text-center">回数</th>
                                        <th class="col-1 col-md-1 text-center">重量 (kg)</th>
                                    </tr>
                                </thead>

                                <!-- データ部分 -->
                                <tbody>
                                    <tr class="menu-row text-center">
                                        <input type="hidden" name="menu_exercises[{{ $index }}][id]" value="{{ $menuExercise->id }}">
                                        <input type="hidden" name="menu_exercises[{{ $index }}][exercise_id]"  value="{{ $menuExercise->exercise->id }}">                                            
                                        <td>                    
                                            <span class="set-number" name="menu_exercises[{{ $index }}][order]">{{ $menuExercise->order }}</span>
                                        </td>
                                        
                                        <td>
                                            <input type="number" name="menu_exercises[{{ $index }}][reps]" value="{{ $menuExercise->reps }}" class="form-control text-center">
                                            <!-- 回数 -->
                                        </td>
                                        <td>
                                            <input type="number" name="menu_exercises[{{ $index }}][weight]" value="{{ $menuExercise->weight }}" class="form-control text-center">
                                            <!-- 重量 -->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group mt-3">
                            <label for="memo">メモ:</label>
                            <textarea class="form-control" rows="2" name="menu_exercises[{{ $index }}][memo]" rows="3">{{ $menuExercise->memo }}</textarea>
                        </div>
                        <div class="text-center mt-3 row">
                            <div class="col-6">
                                <button type="button" class="btn btn-primary w-75 add-menu"
                                    data-exercise-id="{{ $menuExercise->id }}">セット追加</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger w-75 delete-button"
                                    data-id="{{ $menuExercise->id }}">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                @endforeach
                @endif

                <div class="row mt-2">
                    <!-- Button trigger modal -->
                    <div class="col-12 mt-3">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" id="modal_bt">
                                    種目追加
                                </button>
                            </div>

                            <div class="col-6 text-end">
                                <button type="submit" class="btn btn-primary">更新</button>
                            </div>
                        </div>

                    </div>
                </div>


            </form>
               
     
     
        </div>
        <!--ーーーーーーーーーーーーーーーーーーーーーーーーーーモーダルーーーーーーーーーーーーーーーーーーーー-->


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">追加種目選択</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="exercises-form" action="{{ route('add_exercise') }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                            @foreach ($exercises as $body_part => $exercises_in_body_part)
                                <div>
                                    <h5>{{ $body_part }}</h5>
                                    <div class="d-flex flex-wrap justify-content-start">
                                        @foreach ($exercises_in_body_part as $exercise)
                                            <div class="form-check m-2">
                                                <input class="form-check-input" type="checkbox"
                                                    value="{{ $exercise->id }}" id="exercise{{ $exercise->id }}"
                                                    name="selectedExercises[]">
                                                <label class="form-check-label" for="exercise{{ $exercise->id }}">
                                                    {{ $exercise->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach


                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                            <button type="submit" class="btn btn-info" id="add-exercises">種目追加</button>

                        </form>
                    </div>
                </div>
            </div>
            
        </div>





        <!--ーーーーーーーーーーーーーーーーーーーーーーーーーーモーダルーーーーーーーーーーーーーーーーーーーー-->
    </x-slot>

    <x-slot name="script">
        <script>
            // ---------------------メニュー追加ボタンを押したときの処理--------------------------------------
            // ドキュメントが読み込まれた後に実行
            $(document).ready(function() {
                // '.add-menu'クリック時のイベントハンドラを追加
                $(document).on('click', '.add-menu', function() {

                    // クリックされたボタンのdata-exercise-idを取得
                    var exerciseId = $(this).data('exercise-id');

                    // 親要素から種目ブロックを取得
                    var exerciseBlock = $('.exercise-block[data-exercise-id="' + exerciseId + '"]');
                    // 種目ブロックの最初の行を複製
                    var newRow = exerciseBlock.find('.menu-row:first').clone();


                    // 新しいインデックスを計算
                    var newIndex = exerciseBlock.find('.menu-row').length;


                    // 新しい行の全入力要素をループ

                    newRow.find('input[name^="menu_exercises"]').each(function() {
                        // 入力要素のname属性を更新（既存のインデックスを新しいインデックスに書き換え）
                        var newName = this.name.replace(/\[\d+\]/, '[' + newIndex + ']');
                        this.name = newName;
                    });
                    newRow.find('input[name^="menu_exercises"][name$="[id]"]').val('new');
                    // 新しい行の回数、重量、メモの入力欄をリセット
                    newRow.find(

                        'input[name^="menu_exercises"][name$="[reps]"], input[name^="menu_exercises"][name$="[weight]"], input[name^="menu_exercises"][name$="[memo]"]'
                    ).val('');

                    // 新しい行に'new-row'クラスを追加
                    newRow.addClass('new-row');

                    // 種目ブロックの最後の行のセット数を取得
                    var lastSetNumber = exerciseBlock.find('.menu-row:last .set-number').text();
                    // 新しいセット数を計算（最後のセット数がNaNなら1、そうでなければ最後のセット数に1を足す）
                    var newSetNumber = isNaN(parseInt(lastSetNumber)) ? 1 : parseInt(lastSetNumber) + 1;
                    // 新しい行のセット番号を更新
                    newRow.find('.set-number').text(newSetNumber);

                    // 新しい行をtbodyに追加
                    newRow.appendTo(exerciseBlock.find('tbody'));

                });
            });

            // ---------------------メニュー追加ボタンを押したときの処理--------------------------------------

            // ----------------------------------------削除Ajax--------------------------------------------
            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault(); // デフォルトのフォーム送信を防ぐ

                var exerciseBlock = $(this).closest('.exercise-block'); // ボタンが含まれるエクササイズブロックを取得
                var rows = exerciseBlock.find('.menu-row'); // 全ての行を取得

                if (rows.length > 1) {
                    // もし行が複数あるなら、最後の行を削除する
                    rows.last().remove();
                } else {
                    // 一行しかない場合は、データベースから削除する
                    var id = $(this).data('id'); // ボタンのdata-id属性からIDを取得

                    $.ajax({
                        url: '/today_destroy/' + id, // 適切なURLに修正してください
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'), // CSRFトークン
                            _method: 'DELETE' // DELETEメソッドを指定
                        },
                        success: function(response) {
                            location.href = '/today_edit/' + response.menu_id; // 適切なURLに修正してください
                        }
                    });
                }
            });

            // ----------------------------------------削除Ajax--------------------------------------------

   
           
        </script>



    </x-slot>

</x-base-layout>
