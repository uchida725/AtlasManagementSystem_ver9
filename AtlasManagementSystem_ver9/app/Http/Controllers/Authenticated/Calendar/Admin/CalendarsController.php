<?php

namespace App\Http\Controllers\Authenticated\Calendar\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\Admin\CalendarView;
use App\Calendars\Admin\CalendarSettingView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.admin.calendar', compact('calendar'));
    }

    public function reserveDetail($date, $part){
        $reservePersons = ReserveSettings::with('users')->where('setting_reserve', $date)->where('setting_part', $part)->get();
        return view('authenticated.calendar.admin.reserve_detail', compact('reservePersons', 'date', 'part'));
    }

    public function reserveSettings(){
    $calendar = new CalendarSettingView(time());
    $reserveSettings = ReserveSettings::orderBy('setting_reserve', 'asc')->paginate(10); // ← ここを追加！

    return view('authenticated.calendar.admin.reserve_setting', compact('calendar', 'reserveSettings'));
}


    public function reserveDetailFromUser($user_id, $date, Request $request)
{
    $part = $request->query('part');

    $reservePersons = ReserveSettings::with('users')
        ->where('setting_reserve', $date)
        ->where('setting_part', $part)
        ->get();

    return view('authenticated.calendar.admin.reserve_detail', compact('reservePersons', 'date', 'part'));
}


    public function updateSettings(Request $request){
        $reserveDays = $request->input('reserve_day');
        foreach($reserveDays as $day => $parts){
            foreach($parts as $part => $frame){
                ReserveSettings::updateOrCreate([
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                ],[
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                    'limit_users' => $frame,
                ]);
            }
        }
        return redirect()->route('calendar.admin.setting', ['user_id' => Auth::id()]);
    }




    public function cancel(Request $request)
{
    $date = $request->input('delete_date');
    $userId = auth()->id();

    // ユーザーの予約（中間テーブル）を削除
    $reserve = \App\Models\Calendars\ReserveSettings::where('setting_reserve', $date)
                ->whereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                })
                ->first();

    if ($reserve) {
        $reserve->users()->detach($userId);
        return redirect()->back()->with('success', '予約をキャンセルしました。');
    }

    return redirect()->back()->with('error', '予約が見つかりませんでした。');
}

}
