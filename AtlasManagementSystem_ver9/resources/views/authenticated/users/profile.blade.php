<x-sidebar>
<div class="vh-100 border">
  <div class="top_area_title">
    <span>{{ $user->over_name }}</span><span>{{ $user->under_name }}さんのプロフィール</span>
  </div>

  <div class="top_area w-75 m-auto pt-4">

    <div class="user_status p-3">
      <p>名前 : <span>{{ $user->over_name }}</span><span class="ml-1">{{ $user->under_name }}</span></p>
      <p>カナ : <span>{{ $user->over_name_kana }}</span><span class="ml-1">{{ $user->under_name_kana }}</span></p>
      <p>性別 : @if($user->sex == 1)<span>男</span>@else<span>女</span>@endif</p>
      <p>生年月日 : <span>{{ $user->birth_day }}</span></p>
      <div>選択科目 :
        @foreach($user->subjects as $subject)
        <span>{{ $subject->subject }}</span>
        @endforeach
      </div>
      <div class="subject_edit">
        @can('admin')
        <span class="subject_edit_btn">
          選択科目の編集
          <span class="arrow-icon"></span>
        </span>
        <div class="subject_inner mt-2">
          <form action="{{ route('user.edit') }}" method="post" class="subject_checkbox_area">
            @foreach($subject_lists as $subject_list)
            <label class="mr-3">
              <input type="checkbox" name="subjects[]" value="{{ $subject_list->id }}">
              {{ $subject_list->subject }}
            </label>
            @endforeach
            <div class="mt-2">
              <input type="submit" value="編集" class="btn btn-primary">
              <input type="hidden" name="user_id" value="{{ $user->id }}">
              {{ csrf_field() }}
            </div>
          </form>
        </div>
        @endcan
      </div>
    </div>
  </div>
</div>
</x-sidebar>

<script>
  document.addEventListener('DOMContentLoaded', function () {
  const editBtn = document.querySelector('.subject_edit_btn');
  const arrow = document.querySelector('.arrow-icon');

  editBtn.addEventListener('click', function () {
    arrow.classList.toggle('open'); // 上下をトグル
  });
});

</script>
