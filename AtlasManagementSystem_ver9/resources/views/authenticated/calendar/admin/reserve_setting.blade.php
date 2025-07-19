<x-sidebar>
<div class="w-100 vh-100 d-flex" style="align-items:center; justify-content:center; background:#ECF1F6;">
  <div class="calendar-card">
    <!-- カレンダーと予約設定表示 -->
    {!! $calendar->render() !!}
    <div class="adjust-table-btn m-auto text-right mt-3">
      <input type="submit" class="btn btn-primary school-admin" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
    </div>
  </div>
</div>
</x-sidebar>
