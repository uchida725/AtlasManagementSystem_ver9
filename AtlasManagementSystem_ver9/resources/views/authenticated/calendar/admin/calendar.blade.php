<x-sidebar>
<div class="w-75 m-auto">
  <div class="w-100">
    <p class="title_day">{{ $calendar->getTitle() }}</p>
    <p>{!! $calendar->render() !!}</p>
  </div>
</div>
</x-sidebar>
