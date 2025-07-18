<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;
use Illuminate\Support\Facades\DB;

class CalendarSettingView {
  private $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  public function render(){
    $html = [];
    $html[] = '<p class="calendar-title">'.$this->getTitle().'</p>';

    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table m-auto border adjust-table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border day-sat">土</th>';
    $html[] = '<th class="border day-sun">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();

    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';
      $days = $week->getDays();
      foreach($days as $day){
        $startDay = $this->carbon->format("Y-m-01");
        $toDay = $this->carbon->format("Y-m-d");

if($day->everyDay()){
    $currentMonth = $this->carbon->format('m'); // 現在の月
    $dayMonth = Carbon::parse($day->everyDay())->format('m'); // その日の月

    if($dayMonth !== $currentMonth){
        // 当月以外
        $html[] = '<td class="admin-outside-day border '.$day->getClassName().'">';
    } elseif($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
        // 当月の過去日
        $html[] = '<td class="admin-past-day border '.$day->getClassName().'">';
    } else {
        // 当月の予約可能日
        $html[] = '<td class="admin-available-day border '.$day->getClassName().'">';
    }
} else {
    // 空白セル
    $html[] = '<td class="day-blank border"></td>';
}


        $html[] = $day->render();
        $html[] = '<div class="adjust-area">';

        if($day->everyDay()){
          // ★ count() を使って予約人数を取得
          $reserve1 = ReserveSettings::where('setting_reserve', $day->everyDay())->where('setting_part', 1)->first();
          $reserve2 = ReserveSettings::where('setting_reserve', $day->everyDay())->where('setting_part', 2)->first();
          $reserve3 = ReserveSettings::where('setting_reserve', $day->everyDay())->where('setting_part', 3)->first();

          $count1 = $reserve1 ? DB::table('reserve_setting_users')->where('reserve_setting_id', $reserve1->id)->count() : 0;
          $count2 = $reserve2 ? DB::table('reserve_setting_users')->where('reserve_setting_id', $reserve2->id)->count() : 0;
          $count3 = $reserve3 ? DB::table('reserve_setting_users')->where('reserve_setting_id', $reserve3->id)->count() : 0;

          if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
            $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][1]" type="text" form="reserveSetting" value="'.$day->onePartFrame($day->everyDay()).'" disabled></p>';
            $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][2]" type="text" form="reserveSetting" value="'.$day->twoPartFrame($day->everyDay()).'" disabled></p>';
            $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][3]" type="text" form="reserveSetting" value="'.$day->threePartFrame($day->everyDay()).'" disabled></p>';
          }else{
            $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][1]" type="text" form="reserveSetting" value="'.$day->onePartFrame($day->everyDay()).'"></p>';
            $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][2]" type="text" form="reserveSetting" value="'.$day->twoPartFrame($day->everyDay()).'"></p>';
            $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][3]" type="text" form="reserveSetting" value="'.$day->threePartFrame($day->everyDay()).'"></p>';
          }
        }

        $html[] = '</div>';
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }

    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="'.route('calendar.admin.update').'" method="post" id="reserveSetting">'.csrf_field().'</form>';
    return implode("", $html);
  }

  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
