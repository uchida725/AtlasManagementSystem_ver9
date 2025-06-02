<x-sidebar>
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="w-50 m-auto h-75">

    {{-- ★ 該当日時を表示 --}}
    <p><span>{{ $date }}日</span><span class="ml-3">{{ $part }}部</span></p>

    <div class="h-75 border">
      <table class="w-100">
        <tr class="text-center">
          <th class="w-25">ID</th>
          <th class="w-25">名前</th>
          <th class="w-25">場所</th>
        </tr>

        {{-- ★ 予約者情報を表示 --}}
        @foreach($reservePersons as $person)
          @foreach($person->users as $user)
            <tr class="text-center">
              <td class="w-25">{{ $user->id }}</td>
              <td class="w-25">{{ $user->over_name }}{{ $user->under_name }}</td>
              <td class="w-25">リモート</td>
            </tr>
          @endforeach
        @endforeach

      </table>
    </div>
  </div>
</div>
</x-sidebar>
