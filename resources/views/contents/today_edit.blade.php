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
            @if ($menu)
                <h1 class="text-center mt-5">今日のメニュー編集</h1>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form action="{{ route('today_update', ['id' => $menu->id]) }}" method="POST">
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


                    <div class="col-12 mt-3">

                        <h4>メニュー名</h4>

                        <input type="text" name="name" value="{{ $menu->name }}" class="form-control">


                        @if ($menu->menuExercises->isEmpty())
                            <h4 class="text-center mt-5">種目が登録されていません。</h4>
                        @endif

                    </div>

                    <!-- メニュー編集部分 -->
                    @php
                        $globalIndex = 0;
                    @endphp
                    @foreach ($menu->menuExercises->groupBy('exercise_id') as $exerciseId => $menuExercisesForExercise)
                        <div class="exercise-block" data-exercise-id="{{ $exerciseId }}">
                            <!-- 種目を囲むdiv -->
                            <h3 class="text-start mt-3">{{ $menuExercisesForExercise->first()->exercise->name }}</h3>

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
                                        @php
                                            $setIndex = 0;
                                        @endphp
                                        @foreach ($menuExercisesForExercise as $menuExercise)
                                            <tr class="menu-row text-center">
                                                <input type="hidden" name="menu_exercises[{{ $globalIndex }}][id]"
                                                    value="{{ $menuExercise->id }}">
                                                <input type="hidden"
                                                    name="menu_exercises[{{ $globalIndex }}][exercise_id]"
                                                    value="{{ $menuExercise->exercise->id }}">
                                                <td>
                                                    <span class="set-number">{{ $menuExercise->set }}</span>
                                                    <input type="hidden"
                                                        name="menu_exercises[{{ $globalIndex }}][set]"
                                                        value="{{ $menuExercise->set }}" class="set-number">
                                                </td>

                                                <td>
                                                    <input type="number"
                                                        name="menu_exercises[{{ $globalIndex }}][reps]"
                                                        value="{{ $menuExercise->reps }}"
                                                        class="form-control text-center">
                                                    <!-- 回数 -->
                                                </td>

                                                <td>
                                                    <input type="number"
                                                        name="menu_exercises[{{ $globalIndex }}][weight]"
                                                        value="{{ $menuExercise->weight }}"
                                                        class="form-control text-center">

                                                    <!-- 重量 -->
                                                </td>

                                            </tr>
                                            @if ($setIndex == 0)
                                                <!-- メモ -->
                                                <div class="col-12">
                                                    <label for="memo">メモ:</label>
                                                    <textarea class="form-control" rows="2" name="menu_exercises[{{ $globalIndex }}][memo]" rows="3">{{ $menuExercise->memo }}</textarea>
                                                    @error('menu_exercises.' . $globalIndex . '.memo')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @php
                                                    $setIndex++;
                                                @endphp
                                            @endif
                                            @php
                                                $globalIndex++;
                                            @endphp
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>


                            <div class="text-center mt-3 row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary w-75 add-menu"
                                        data-exercise-id="{{ $exerciseId }}">セット追加</button>
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


                    @if (!$menu->menuExercises->isEmpty())
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
                    @endif


                </form>
            @else
                <h1 class="text-center mt-5">メニューがありません。</h1>
            @endif


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
                    @error('selectedExercises')
                        <div id="selectedExercises-error" class="alert alert-danger">{{ $message }}</div>
                    @enderror




                    <div class="modal-body">
                        <form id="exercises-form" action="{{ route('add_exercise') }}" method="POST">
                            @if ($menu)
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

                                                    <label class="form-check-label"
                                                        for="exercise{{ $exercise->id }}">
                                                        {{ $exercise->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach



                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                                <button type="submit" class="btn btn-info" id="add-exercises">種目追加</button>
                            @else
                                <h1 class="text-center mt-5">メニューがありません。</h1>
                            @endif

                        </form>

                    </div>
                </div>
            </div>


        </div>


        @if (session('showModal'))
            <script>
                $(document).ready(function() {
                    $('#exampleModal').modal('show');
                });
            </script>
        @endif



        <!--ーーーーーーーーーーーーーーーーーーーーーーーーーーモーダルーーーーーーーーーーーーーーーーーーーー-->
    </x-slot>

    <x-slot name="script">
        <script>
            // ---------------------メニュー追加ボタンを押したときの処理--------------------------------------
            // ドキュメントが読み込まれた後に実行
            $(document).ready(function() {
                $(document).on('click', '.add-menu', function() {
                    var exerciseId = $(this).data('exercise-id');
                    var exerciseBlock = $('.exercise-block[data-exercise-id="' + exerciseId + '"]');
                    var newRow = exerciseBlock.find('.menu-row:first').clone();
                    var indices = $('input[name^="menu_exercises"]').map(function() {
                        var match = this.name.match(/\[(\d+)\]/);
                        return match ? parseInt(match[1]) : null;
                    }).get();
                    var newIndex = Math.max.apply(null, indices) + 1;

                    newRow.find('input[name^="menu_exercises"]').each(function() {
                        var newName = this.name.replace(/\[\d+\]/, '[' + newIndex + ']');
                        this.name = newName;
                    });
                    newRow.find('input[name^="menu_exercises"][name$="[id]"]').val('new');
                    newRow.find(
                        'input[name^="menu_exercises"][name$="[set]"], input[name^="menu_exercises"][name$="[reps]"], input[name^="menu_exercises"][name$="[weight]"], input[name^="menu_exercises"][name$="[memo]"]'
                    ).val('');
                    newRow.addClass('new-row');


                    // var lastSetNumberText = exerciseBlock.find('.menu-row:last .set-number').text().trim();
                    //var matches = lastSetNumberText.match(/\d+$/);
                    var lastSetNumber = parseInt(exerciseBlock.find('.menu-row:last input[name$="[set]"]')
                        .val(), 10) || 0;
                    var newSetNumber = isNaN(lastSetNumber) ? 1 : lastSetNumber + 1;
                    console.log(lastSetNumber);
                    console.log(newSetNumber);
                    newRow.find('.set-number').text(newSetNumber);
                    newRow.find('input[name^="menu_exercises"][name$="[set]"]').val(newSetNumber);
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
                            location.href = '/today_edit/' + response
                                .menu_id; // 適切なURLに修正してください
                        }
                    });
                }
            });
            // ----------------------------------------種目追加モーダルエラーの再表示--------------------------------------------
        </script>



    </x-slot>

</x-base-layout>
