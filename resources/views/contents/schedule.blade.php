<x-base-layout>
    <x-slot name="title">筋トレスケジュール</x-slot>
    <x-slot name="css">
        <style>
            .add-menu-button {
                font-size: 15px;
                /* Adjust size as needed */
                color: white;
                /* Adjust color as needed */
                background-color: green;
                /* Adjust color as needed */
                border: none;
                /* Adjust as needed */
                padding: 3px 6px;
                /* Adjust padding as needed */
                cursor: pointer;
            }
        </style>
    </x-slot>

    <x-slot name="main">
        <div class="container">
            <h2 class="mt-4 text-center">筋トレスケジュール</h2>

            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif


            @if (!$menuExists)
                <button type="button" class="btn btn-primary w-25 add-menu-button" data-bs-toggle="modal"
                    data-bs-target="#newModal">
                    メニュー追加
                </button>
            @endif



            <div class="accordion accordion-flush" id="accordionFlushExample">
                @foreach ($menus as $menuIndex => $menu)
                    <div class="accordion-item" data-order="{{ $menu->order }}" data-id="{{ $menu->id }}">
                        <h2 class="accordion-header row">
                            <div class="col-11">
                                <button class="accordion-button collapsed pe-0" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapse{{ $menuIndex }}" aria-expanded="false"
                                    aria-controls="flush-collapse{{ $menuIndex }}"> {{ $menu->name }}
                                </button>
                            </div>
                            <div class="col-1 text-center  px-0">
                                <button type="button" class="btn btn-primary add-menu-button" data-bs-toggle="modal"
                                    data-bs-target="#newModal" data-menu-index="{{ $menuIndex }}">＋
                                </button>
                            </div>

                        </h2>

                        <div id="flush-collapse{{ $menuIndex }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="col-12 text-start">
                                    @if ($menu->menuExercises)
                                        @php
                                            $groupedMenuExercises = $menu->menuExercises->groupBy('exercise.name');
                                            $workoutNo = 1;
                                        @endphp
                                        <table class="table" style="table-layout: fixed;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 20%;">Ｎｏ</th>
                                                    <th class="text-center" style="width: 50%;">種目名</th>
                                                    <th style="width: 12.5%;">重量</th>
                                                    <th style="width: 12.5%;">回数</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($groupedMenuExercises as $exerciseName => $menuExercises)
                                                    @foreach ($menuExercises as $exerciseIndex => $menuExercise)
                                                        <tr>
                                                            <th class="text-center" scope="row">
                                                                {{ $exerciseIndex == 0 ? $workoutNo++ : '' }}</th>
                                                            <td class="text-center" style="width: 50%;">
                                                                {{ $menuExercise->exercise->name }}
                                                            </td>
                                                            <td class="text-center" style="width: 12.5%;">
                                                                {{ $menuExercise->weight }}
                                                            </td>
                                                            <td class="text-center" style="width: 12.5%;">
                                                                {{ $menuExercise->reps }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="mt-2">このメニューには内容が登録されていません。</p>
                                    @endif
                                    <div class="row text-center mb-5">
                                        <!-- Buttons here -->
                                        <div class="col">
                                            <div style="max-width: 80%; margin: auto;">
                                                <a href="{{ route('schedule.edit', $menu->id) }}"
                                                    class="btn btn-primary w-100">編集</a>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div style="max-width: 80%; margin: auto;">
                                                <button type="button" class="btn btn-primary w-100 add-menu-button"
                                                    data-bs-toggle="modal" data-bs-target="#newModal"
                                                    data-menu-index="{{ $menuIndex }}">メニュー追加
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div style="max-width: 80%; margin: auto;">
                                                <button type="button" class="btn btn-danger w-100"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-menu-id="{{ $menu->id }}"
                                                    data-menu-name="{{ $menu->name }}">削除
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>



        <!--ーーーーーーーーーーーーーーーーーーーーーーーーーー削除モーダルーーーーーーーーーーーーーーーーーーーー-->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">削除の確認</h5>
                    </div>
                    <div class="modal-body">
                        <p class="text-center" id="menuName"></p>
                        <p class="text-center">このメニューを削除してもよろしいですか？</p>
                    </div>
                    <div class="modal-footer">
                        <div class="mx-auto">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                            <button type="button" class="btn btn-danger ms-5" id="confirmDelete">削除する</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--ーーーーーーーーーーーーーーーーーーーーーーーーーーメニュー追加モーダルーーーーーーーーーーーーーーーーーーーー-->

        <!-- Modal -->
        <div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newModalLabel">新メニュー作成</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>

                    </div>
                    <div class="modal-body">
                        <form id="exercises-form" action="{{ route('add_menu') }}" method="POST">
                            @csrf
                            <input type="hidden" id="insert-position" name="insert_position">
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-primary mx-auto mb-2" data-bs-toggle="modal"
                                    data-bs-target="#newExerciseModal" id="new-exercises">種目を追加</button>
                            </div>

                            @error('selectedExercises')
                                <div id="selectedExercises-error" class="alert alert-danger">{{ $message }}
                                </div>
                            @enderror
                            <div class="mb-3">
                                <label for="menuName" class="form-label">メニュー名</label>
                                <input type="text" class="form-control" id="menuname" name="menu_name" required>
                                @error('menu_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

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


                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">閉じる</button>
                                    <button type="submit" class="btn btn-info" id="add-exercises">メニュー作成</button>
                                </div>


                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>





        <!--ーーーーーーーーーーーーーーーーーーーーーーーーーーモーダルーーーーーーーーーーーーーーーーーーーー-->

        <!--ーーーーーーーーーーーーーーーーーーーーーーーーーー新しい種目追加のモーダルーーーーーーーーーーーーーーーーーーーーーーーーーーーー-->
        <!-- New Exercise Modal -->
        <div class="modal fade" id="newExerciseModal" tabindex="-1" aria-labelledby="newExerciseModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newExerciseModalLabel">新しい種目を追加する</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="new-exercise-form" action="{{ route('add_new_exercise') }}" method="POST">
                            @csrf
                            @error('body_part')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div id="body-part-error"></div>
                            <div class="mb-3">
                                <label for="exerciseName" class="form-label">種目名</label>
                                <input type="text" class="form-control" id="exerciseName" name="exercise_name"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="bodyPart" class="form-label">部位</label>
                                <div class="input-group">
                                    <select class="form-select" id="bodyPart" name="body_part" aria-label="選択する部位">
                                        <option selected value="">既存の部位を選択</option>
                                        @foreach ($body_parts as $body_part)
                                            <option value="{{ $body_part }}">{{ $body_part }}</option>
                                        @endforeach
                                    </select>


                                    <input type="text" class="form-control" id="newBodyPart" name="new_body_part"
                                        placeholder="新しい部位を追加" aria-label="新しい部位">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">種目追加</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>






    </x-slot>

    <x-slot name="script">
        <script>
            //-------------------------------メニューのソート------------------------------------------
            $(function() {
                var accordion = $("#accordionFlushExample");

                // SortableJSの初期化
                var sortable = new Sortable(accordion[0], {
                    animation: 150,
                    handle: '.accordion-header', // ドラッグハンドルとして使用する要素
                    onEnd: function(evt) {
                        var order = {};
                        $('.accordion-item').each(function(index) {
                            order[$(this).data('id')] = index;
                        });
                        console.log(order);
                        // Ajaxリクエストで新しい順序をサーバーに送信
                        $.ajax({
                            url: '{{ route('update_menu_order') }}', // 適切なURLに変更してください
                            type: 'POST',
                            data: {
                                order: order,
                                _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
                            },
                            success: function(data) {
                                console.log(data)
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error("Ajax request failed: ", textStatus, ", ",
                                    errorThrown);
                            }
                        });
                    }
                });
            });
            //ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー削除モーダルーーーーーーーーーーーーーーーーーーーーーーーーーーーー-->
            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var menuId = button.data('menu-id') // Extract info from data-* attributes
                var menuName = button.data('menu-name') // Extract info from data-* attributes

                // Set the menu details in the modal
                var modal = $(this)
                modal.find('.modal-body #menuName').text(menuName)
                modal.find('.modal-footer #confirmDelete').data('menuId', menuId)
            })

            $('#confirmDelete').on('click', function() {
                var menuId = $(this).data('menuId');
                deleteMenu(menuId);
                // Send AJAX DELETE request to your server here
            });

            function deleteMenu(menuId) {
                // Set the cursor to 'wait' to indicate that the request is being processed
                document.body.style.cursor = 'wait';

                // Set up the data to be sent in the AJAX request
                var fd = new FormData();
                fd.append("_token", $('meta[name=csrf-token]').attr('content')); // Add CSRF token
                fd.append("menuId", menuId); // Add the menu id

                // Send the AJAX request
                $.ajax({
                        url: "./menu_delete", // Endpoint where the menu will be deleted
                        type: "POST",
                        data: fd,
                        processData: false,
                        contentType: false,
                        timeout: 10000,
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            console.error(XMLHttpRequest, textStatus, errorThrown);
                            document.body.style.cursor = 'auto';
                        }
                    })
                    .done(function(res) {
                        // Reload the page when the request is successful
                        location.reload();
                    })
                    .fail(function(res) {
                        // Handle error here
                        console.error(res);
                    });
            }

            //ーーーーーーーーーーーーーーーーーーーーーーーーーーーー新しい種目を追加、モーダルが自動で閉じるファンクションーーーーーーーーーーーーーーーーーーーーーーーーーー

            $(document).ready(function() {
                // Get the modals
                var newMenuModal = new bootstrap.Modal(document.getElementById('newModal'));
                var newExerciseModal = new bootstrap.Modal(document.getElementById('newExerciseModal'));

                // When the 'Add New Exercise' button is clicked, close the new menu modal and open the new exercise modal
                $('#new-exercises').on('click', function() {
                    newMenuModal.hide();
                    newExerciseModal.show();
                });

                // AJAXで種目を追加する
                $('#new-exercise-form').submit(function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: '{{ route('add_new_exercise') }}',
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            // データが成功したら、新しいエクササイズモーダルを非表示にする
                            $('#newExerciseModal').modal('hide');

                            // URLのハッシュを設定する
                            window.location.hash = '#newMenu';

                            // ここでリロードします
                            location.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            var errors = jqXHR.responseJSON.errors;
                            var errorMessage = jqXHR.responseJSON.message ||
                            'エラーが発生しました。'; // 一般的なエラーメッセージ

                            // body_part_combinationやnew_body_partに関連するエラーがあれば取得
                            if (errors) {
                                if (errors.body_part_combination) {
                                    errorMessage = errors.body_part_combination[0];
                                }
                                if (errors.new_body_part) {
                                    errorMessage = errors.new_body_part[0];
                                }
                            }

                            // エラーメッセージをHTMLに追加
                            $('#body-part-error').html('<div class="alert alert-danger">' +
                                errorMessage + '</div>');
                            console.error('Error: ' + textStatus, errorThrown);
                        }
                    });
                });



                // ページのリロードが完了したら、「新メニュー作成」のモーダルを表示する
                // URLのハッシュに基づいて判定する
                if (window.location.hash === '#newMenu') {
                    $('#newModal').modal('show');
                }
            });


            //ーーーーーーーーーーーーーーーーーーーーーーーーーーーーorderを取得するーーーーーーーーーーーーーーーーーーーーーーーーーー

            // ドキュメントが読み込まれた後にJavaScriptコードを実行します
            document.addEventListener('DOMContentLoaded', (event) => {
                // 「メニュー追加」ボタンがクリックされたときのイベントリスナーを追加します
                document.querySelectorAll('.add-menu-button').forEach((button) => {
                    button.addEventListener('click', (event) => {
                        // Set the position of this menu as the insert position
                        const insertPosition = parseInt(button.dataset.menuIndex) +
                            1; // add 1 to insert in between
                        console.log(insertPosition);
                        document.querySelector('#insert-position').value = insertPosition;

                        // ボタンの親要素の親要素（メニューのdiv）のorder値を取得します
                        const menuDiv = event.target.parentElement.parentElement;
                        const order = menuDiv.getAttribute('data-order') ||
                            '0'; // Use '0' as a default value

                        // ここでorder値を使って何かを行うことができます
                        console.log('The order of the clicked menu is ' + order);
                    });
                });


                // メニュー作成ボタンがクリックされたときのイベントリスナーを追加します
                document.querySelector('#add-exercises').addEventListener('click', (event) => {
                    const insertPosition = document.querySelector('#insert-position').value;
                    if (typeof insertPosition === 'undefined' || insertPosition === '') {
                        // If the insert position is not defined, default to '0'
                        document.querySelector('#insert-position').value = '0';
                    }
                });
            });

            // セッションに'showModal'がある場合、モーダルを開く
            @if (session('showModal') == 'true')
                var myModal = new bootstrap.Modal(document.getElementById('newModal'), {});
                myModal.show();
            @endif
        </script>
    </x-slot> </x-base-layout>
