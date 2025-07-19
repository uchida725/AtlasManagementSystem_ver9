<x-sidebar>
<div class="w-75 m-auto">
  <div class="calendar-card">
    <p class="title_day text-center mb-4">{{ $calendar->getTitle() }}</p>
    <div class="setting-calendar">
      {!! $calendar->render() !!}
    </div>
  </div>
</div>
</x-sidebar>
