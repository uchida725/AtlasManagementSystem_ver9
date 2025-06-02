<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Users\User;
use App\Models\Calendars\ReserveSettings;
use Illuminate\Support\Facades\Auth;

class CalendarView{
  private $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  public function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table m-auto border">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border">土</th>';
    $html[] = '<th class="border">日</th>';
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
        if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
          $html[] = '<td class="past-day border">';
        }else{
          $html[] = '<td class="border '.$day->getClassName().'">';
        }
        $html[] = $day->render();
        $html[] = $day->dayPartCounts($day->everyDay());
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';

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
function dayPartCounts($ymd){
    $html = [];
    $user_id = Auth::id();
    $html[] = '<div class="text-left" style="font-size: 12px; line-height: 1.4;">';

    // 1部
    $reserve1 = ReserveSettings::where('setting_reserve', $ymd)
        ->where('setting_part', 1)
        ->first();
    $count1 = $reserve1 ? $reserve1->users()->count() : 0;
    $url1 = url("/calendar/{$user_id}/reserve/{$ymd}") . '?part=1';
    $html[] = '<p class="day_part m-0 pt-1"><a href="' . $url1 . '">1部</a> ' . $count1 . '</p>';

    // 2部
    $reserve2 = ReserveSettings::where('setting_reserve', $ymd)
        ->where('setting_part', 2)
        ->first();
    $count2 = $reserve2 ? $reserve2->users()->count() : 0;
    $url2 = url("/calendar/{$user_id}/reserve/{$ymd}") . '?part=2';
    $html[] = '<p class="day_part m-0 pt-1"><a href="' . $url2 . '">2部</a> ' . $count2 . '</p>';

    // 3部
    $reserve3 = ReserveSettings::where('setting_reserve', $ymd)
        ->where('setting_part', 3)
        ->first();
    $count3 = $reserve3 ? $reserve3->users()->count() : 0;
    $url3 = url("/calendar/{$user_id}/reserve/{$ymd}") . '?part=3';
    $html[] = '<p class="day_part m-0 pt-1"><a href="' . $url3 . '">3部</a> ' . $count3 . '</p>';

    $html[] = '</div>';
    return implode("", $html);
}

}
