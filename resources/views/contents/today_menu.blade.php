<x-base-layout>
    <!-- カレンダー表示 -->

    <x-slot name="title">ホーム</x-slot>
    <x-slot name="css">
        <style type="text/css">
            .set-header .short-form {
                display: none;
            }

            @media screen and (max-width: 600px) {
                .set-header .short-form {
                    display: inline;
                }

                .set-header .full-form {
                    display: none;
                }
            }
        </style>
    </x-slot>
    <x-slot name="main">
        <div class="container">

            @if (Session::has('message'))
                <div class="alert alert-info">
                    {{ Session::get('message') }}
                </div>
            @endif

            @if ($menu)
                <div class="row align-items-center">


                    <div class="col-12 text-center">
                        <h1>{{ \Carbon\Carbon::now()->format('Y/m/d') }}</h1>
                    </div>

                    <div class="col-12">
                        <h2>今日の種目：{{ $menu->name }}</h2>
                    </div>

                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal"
                        data-bs-target="#confirmModal">
                        メニュー完了
                    </button>


                    @if (count($menu->menuExercises->groupBy('exercise_id')) >= 2)
                        <div class="col-10 mx-auto mt-2 ">
                            <a href="{{ route('today_edit', ['id' => $menu->id]) }}"
                                class="btn btn-primary btn-lg w-100">種目の編集</a>
                        </div>
                    @endif
                </div>


                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('complete_menu', ['id' => $menu->id]) }}" method="POST">
                    @csrf
                    @foreach ($menu->menuExercises->groupBy('exercise_id') as $exerciseId => $menuExercisesForExercise)
                        @php
                            $todayStart = \Carbon\Carbon::today();
                            $todayEnd = \Carbon\Carbon::tomorrow();

                            $allSetsCompleted = $menuExercisesForExercise->every(function ($menuExercise) use ($todayStart) {
                                return $menuExercise->histories->where('exercise_date', $todayStart->format('Y-m-d'))->isNotEmpty();
                            });
                        @endphp
                        <div style="background-color: {{ $loop->iteration % 2 == 0 ? '#f8f9fa' : '#e9ecef' }};">
                            <div class="row align-items-center mt-3">
                                <div class="col-md-12 d-flex justify-content-between align-items-center">
                                    <h3>{{ $menuExercisesForExercise->first()->exercise->name }}</h3>
                                    @if (!$allSetsCompleted)
                                        <button type="submit" class="btn btn-success me-2 mt-2">Complete</button>
                                    @endif
                                </div>

                                <div class="col-md-12 text-end">
                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th scope="col" class="set-header">
                                                    <span class="full-form">セット</span>
                                                    <span class="short-form">＃</span>
                                                </th>

                                                <th scope="col">回数</th>
                                                <th scope="col">重量 (kg)</th>
                                                <th scope="col" class="set-header">
                                                    <span class="full-form">完了</span>
                                                    <span class="short-form">完</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        @foreach ($menuExercisesForExercise as $menuExercise)
                                            @php
                                                $isHistoryEmptyForToday = $menuExercise->histories->whereBetween('created_at', [$todayStart, $todayEnd])->isEmpty();
                                            @endphp
                                            @if ($isHistoryEmptyForToday)
                                                <tr class="text-center">
                                                    <th scope="row">{{ $menuExercise->set }}</th>
                                                    <td>
                                                        @if ($menuExercise->reps)
                                                            {{ $menuExercise->reps }}
                                                        @else
                                                            <div class="alert alert-danger">Repsの情報がありません。</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($menuExercise->weight)
                                                            {{ $menuExercise->weight }}
                                                        @else
                                                            <div class="alert alert-danger">Weightの情報がありません。</div>
                                                        @endif
                                                    </td>
                                                    <td><input type="checkbox" name="completed_exercises[]"
                                                            value="{{ $menuExercise->id }}"></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </form>



                <div class="col-10 mx-auto mt-5 ">
                    <a href="{{ route('today_edit', ['id' => $menu->id]) }}"
                        class="btn btn-primary btn-lg w-100">種目の編集</a>
                </div>
            @else
                <h1 class="text-center mt-5">メニューがありません。</h1>
            @endif
        </div>

        <!----------------------------------------------- Modal ----------------------------------------------------------------->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">確認</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        メニューを完了しますか？
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                        @if ($menu)
                            <form action="{{ route('today_complete', ['id' => $menu->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">完了する</button>
                            </form>
                        @else
                            <p>メニューがありません。</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </x-slot>







    <x-slot name="script">
        <script></script>
    </x-slot>

</x-base-layout>
