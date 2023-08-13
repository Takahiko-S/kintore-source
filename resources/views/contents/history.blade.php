<x-base-layout>
    <x-slot name="title">実施したエクササイズの履歴</x-slot>
    <x-slot name="css"></x-slot>
    <x-slot name="main">
        <div class="container">
            @if ($history && count($history) > 0)
                <div class="row align-items-center mt-5">
                    <div class="col-12 text-center mb-5">
                        <h1>{{ $date }} 実施メニュー : {{ $menu_name }}</h1>
                    </div>
                </div>

                <div class="col-md-12 text-end">
                    <table class="table">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">エクササイズ名</th>
                                <th scope="col">セット</th>
                                <th scope="col">回数</th>
                                <th scope="col">重量 (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $exercise)
                                <tr class="text-center">
                                    <td>{{ $exercise->exercise_name }}</td> <!-- エクササイズ名があるカラム名に変更してください -->
                                    <td>{{ $exercise->sets }}</td>
                                    <td>{{ $exercise->reps }}</td>
                                    <td>{{ $exercise->weight }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <h1>この日に実施したエクササイズはありません。</h1>
            @endif
        </div>
    </x-slot>

    <x-slot name="script"></x-slot>

</x-base-layout>
