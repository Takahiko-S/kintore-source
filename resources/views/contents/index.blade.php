<x-base-layout>
    <!-- カレンダー表示 -->

    <x-slot name="title">ホーム</x-slot>
    <x-slot name="css">
        <style type="text/css">
            .pointer {
                cursor: pointer;
            }

            .calendar-cell {
                background-color: red !important;
                /* 背景色を淡い青に設定 */
            }

            .completed-exercise {
                background-color: rgb(220, 106, 18) !important;
            }
        </style>
    </x-slot>

    <x-slot name="main">
        @csrf
        <div class="container">
            <div class="row pt-5">

                <div class="col-12">
                    <h1 class="text-center text-primary mb-4">{{ $year }}年{{ $month }}月</h1>
                </div>

                <div class="col-6 text-start mb-2">
                    <a href="{{ route('history.index') }}?y={{ $prevYear }}&m={{ $prevMonth }}"
                        class="btn btn-secondary btn-sm" id="befor">&lt;&lt;&nbsp;前の月</a>
                </div>

                <div class="col-6 text-end mb-2">
                    <a href="{{ route('history.index') }}?y={{ $nextYear }}&m={{ $nextMonth }}"
                        class="btn btn-secondary btn-sm" id="after">次の月&nbsp;&gt;&gt;</a>
                </div>

                <div class="col-12">
                    <table class="table table-striped">
                        <tr class="text-center">
                            <th class="text-danger">日</th>
                            <th>月</th>
                            <th>火</th>
                            <th>水</th>
                            <th>木</th>
                            <th>金</th>
                            <th class="text-primary">土</th>
                        </tr>
                        @foreach ($calendar_data as $index => $data)
                            @if ($index % 7 == 0)
                                <tr>
                            @endif
                            @php
                                $isExerciseDate = in_array(['year' => $year, 'month' => $data['month'], 'day' => $data['day']], $exerciseDates);
                                
                            @endphp


                            @if ($isExerciseDate)
                                <td class="text-center table-striped completed-exercise">
                                    <a href="{{ route('show_history', ['date' => "{$data['year']}-{$data['month']}-{$data['day']}"]) }}"
                                        style="display: block; height: 100%; color: inherit; text-decoration: none;">
                                        {{ $data['day'] }}
                                    </a>
                                </td>
                            @else
                                <td class="text-center table-striped">
                                    {{ $data['day'] }}
                                </td>
                            @endif




                            @if ($index % 7 == 6)
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>

            </div>
            <div class="row">

                <div class="col-10 mx-auto mt-5">
                    <a href="{{ route('today_menu') }}"class="btn btn-danger btn-lg w-100 ">今日のメニュー</a>
                </div>


                <div class="col-10 mx-auto mt-5">
                    <a href="{{ route('schedule_index') }}" class="btn btn-primary btn-lg w-100 ">筋トレスケジュール</a>
                </div>


            </div>

        </div>



        <x-slot name="script"></x-slot>
    </x-slot>

</x-base-layout>
