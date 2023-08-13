<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->input('m') ? intval($request->input('m')) : intval(date('m'));
        $year = $request->input('y') ? intval($request->input('y')) : intval(date('Y'));

        // 前の月と次の月を計算
        $prevMonth = $month - 1;
        $nextMonth = $month + 1;
        $prevYear = $year;
        $nextYear = $year;

        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear = $year - 1;
        }

        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear = $year + 1;
        }

        $calendar_data = $this->makeCalendarData($year, $month);

        foreach ($calendar_data as &$data) {
            $d = explode('-', $data['date']);
            $data['date'] = intval($d[2]);
        }
        // $startDate and $endDate calculation
        $startDate = (new \DateTime($year . "-" . $prevMonth . "-1"))->format('Y-m-01');
        $endDate = (new \DateTime($year . "-" . $nextMonth . "-1"))->format('Y-m-t');

        $exerciseDates = DB::table('histories')
            ->select('exercise_date')
            ->where('user_id', Auth::id())
            ->whereBetween('exercise_date', [$startDate, $endDate])
            ->distinct()
            ->pluck('exercise_date')
            ->map(function ($date) {
                return [
                    'year' => intval(date('Y', strtotime($date))),
                    'month' => intval(date('m', strtotime($date))),
                    'day' => intval(date('d', strtotime($date)))
                ];
            })
            ->toArray();



        return view('contents.index', compact('calendar_data', 'year', 'month', 'prevMonth', 'nextMonth', 'prevYear', 'nextYear', 'exerciseDates'));
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Display the specified resource.
     */


    private function makeCalendarData($year, $month)
    {
        $youbi = array('日', '月', '火', '水', '木', '金', '土',);
        $calendar_array = array();
        //print $month . "<br>";
        // $year = date('Y');
        //print $year . "<br>";
        $date = new \DateTime($year . "-" . $month . "-1");

        $start = $date->format('w');
        $num = -$start . " day";
        if ($start != 0) {
            $date->modify($num);
        }

        for ($i = $start; $i > 0; $i--) {
            array_push($calendar_array, array(
                'date' => $date->format('Y-m-d'),
                'year' => intval($date->format('Y')),
                'day' => intval($date->format('d')), // 'day' key added
                'week' => $youbi[$date->format('w')],
                'month' => intval($date->format('m'))
            ));
            $date->modify('+1 day');
        }

        while ($date->format('m') == $month) {
            array_push($calendar_array, array(
                'date' => $date->format('Y-m-d'),
                'year' => intval($date->format('Y')),
                'day' => intval($date->format('d')), // 'day' key added
                'week' => $youbi[$date->format('w')],
                'month' => intval($date->format('m'))
            ));
            $date->modify('+1 day');
        }
        while ($date->format('w') != 0) {
            array_push($calendar_array, array(
                'date' => $date->format('Y-m-d'),
                'year' => intval($date->format('Y')),
                'day' => intval($date->format('d')), // 'day' key added
                'week' => $youbi[$date->format('w')],
                'month' => intval($date->format('m'))
            ));
            $date->modify('+1 day');
        }
        //print $date->format('Y-m-d') . "<br>";

        return $calendar_array;
    }

    public function top()
    {
        return view('layouts.index');
    }


    public function showHistory($date)
    {
        $history = DB::table('histories')
            ->where('exercise_date', $date)
            ->get();
        $menu_name = History::where('exercise_date', $date)->first()->menu_name;
        //dd($history);
        return view('contents.history', compact('history', 'date', 'menu_name'));
    }
}
