<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView{

  private $carbon;
  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';

    $weeks = $this->getWeeks();
    $today = Carbon::today()->format('Y-m-d');
    $currentMonth = $this->carbon->format('Y-m');

    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';
      $days = $week->getDays();

      foreach($days as $day){
        $dayDate = $day->everyDay();
        $isPast = $dayDate < $today;
        $isSameMonth = strpos($dayDate, $currentMonth) === 0;

        // 背景クラス
        $cellClass = 'calendar-td';
        if ($isPast) $cellClass .= ' bg-past';
        $html[] = '<td class="'.$cellClass.'">';
        $html[] = $day->render();

        // 予約データの取得
        $hasReserve = in_array($dayDate, $day->authReserveDay());

        if ($isPast) {
          if ($hasReserve) {
            $part = $day->authReserveDate($dayDate)->first()->setting_part;
            $html[] = '<p>' . $part . '部参加</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="' . $part . '" form="reserveParts">';
            $html[] = '<input type="hidden" name="getData[]" value="' . $dayDate . '" form="reserveParts">';
          } else if ($isSameMonth) {
            $html[] = '<p>受付終了</p>';
            // 受付終了でも必ず空のhiddenを出して数を合わせる！
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            $html[] = '<input type="hidden" name="getData[]" value="' . $dayDate . '" form="reserveParts">';
          }


        } else {
          if ($hasReserve) {
            $reservePart = $day->authReserveDate($dayDate)->first()->setting_part;
            $label = "";
            if ($reservePart == 1) { $label = "リモ1部"; }
            if ($reservePart == 2) { $label = "リモ2部"; }
            if ($reservePart == 3) { $label = "リモ3部"; }

            $html[] = '<button type="button" class="btn btn-danger p-0 w-75 cancel-modal-btn"
            style="font-size:12px"
            data-bs-toggle="modal"
            data-bs-target="#cancelModal"
            data-reserve="'. $day->authReserveDate($dayDate)->first()->setting_reserve .'"
            data-part="'. $label .'">' . $label . '</button>';

            // getPart[] と getData[] を追記し、両方が表示されるようにする
            $html[] = '<input type="hidden" name="getPart[]" value="' . $reservePart . '" form="reserveParts">';
            $html[] = '<input type="hidden" name="getData[]" value="' . $day->everyDay() . '" form="reserveParts">';

          } else {
            $html[] = $day->selectPart($dayDate);

            // ▼ 予約フォームの選択肢に対応する hidden を追加（空の状態で）
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            $html[] = '<input type="hidden" name="getData[]" value="' . $day->everyDay() . '" form="reserveParts">';
          }
        }

        $html[] = $day->getDate();
        $html[] = '</td>';
      }

      $html[] = '</tr>';
    }

    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';

    return implode('', $html);
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
