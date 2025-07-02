<?php
namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CalendarWeekDay{
  protected $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  function getClassName(){
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function render(){
    return '<p class="day">' . $this->carbon->format("j") . '日</p>';
  }

  function everyDay(){
    return $this->carbon->format("Y-m-d");
  }

  public function getReserveCount($part)
{
    $reserve = ReserveSettings::where('setting_reserve', $this->everyDay())->where('setting_part', $part)->first();
    return $reserve ? DB::table('reserve_setting_users')->where('reserve_setting_id', $reserve->id)->count() : 0;
}

  function dayPartCounts($ymd){
    $html = [];

    $user_id = Auth::id(); // 👈 ログインユーザーのIDを取得

    $html[] = '<div class="text-left">';

    if (ReserveSettings::where('setting_reserve', $ymd)->where('setting_part', '1')->exists()) {
        $url = url("/calendar/{$user_id}/reserve/{$ymd}") . '?part=1';
        $count1 = $this->getReserveCount(1);
        $html[] = '<p class="day_part m-0 pt-1"><a href="' . $url . '">1部</a>（'.$count1.'人予約中）</p>';
    }

    if (ReserveSettings::where('setting_reserve', $ymd)->where('setting_part', '2')->exists()) {
        $url = url("/calendar/{$user_id}/reserve/{$ymd}") . '?part=2';
        $count2 = $this->getReserveCount(2);
        $html[] = '<p class="day_part m-0 pt-1"><a href="' . $url . '">2部</a>（'.$count2.'人予約中）</p>';
    }

    if (ReserveSettings::where('setting_reserve', $ymd)->where('setting_part', '3')->exists()) {
        $url = url("/calendar/{$user_id}/reserve/{$ymd}") . '?part=3';
        $count3 = $this->getReserveCount(3);
        $html[] = '<p class="day_part m-0 pt-1"><a href="' . $url . '">3部</a>（'.$count3.'人予約中）</p>';
    }

    $html[] = '</div>';
    return implode("", $html);
}
  function onePartFrame($day){
    $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first();
    if($one_part_frame){
      $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first()->limit_users;
    }else{
      $one_part_frame = "20";
    }
    return $one_part_frame;
  }
  function twoPartFrame($day){
    $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first();
    if($two_part_frame){
      $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first()->limit_users;
    }else{
      $two_part_frame = "20";
    }
    return $two_part_frame;
  }
  function threePartFrame($day){
    $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first();
    if($three_part_frame){
      $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first()->limit_users;
    }else{
      $three_part_frame = "20";
    }
    return $three_part_frame;
  }

  //
  function dayNumberAdjustment(){
    $html = [];
    $html[] = '<div class="adjust-area">';
    $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="1" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="2" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="3" type="text" form="reserveSetting"></p>';
    $html[] = '</div>';
    return implode('', $html);
  }
}
