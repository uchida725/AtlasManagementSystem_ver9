<x-sidebar>
<!-- <p>ユーザー検索</p> -->
<div class="search_content w-100 d-flex">
  <div class="reserve_users_area">
    @foreach($users as $user)
    <div class="one_person">
      <div class="user_search_profile">
        <div>
        <span>ID : </span><span class="user_select">{{ $user->id }}</span>
      </div>
      <div><span>名前 : </span>
        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
          <span>{{ $user->over_name }}</span>
          <span>{{ $user->under_name }}</span>
        </a>
      </div>
      <div>
        <span>カナ : </span>
        <span class="user_select">({{ $user->over_name_kana }}</span>
        <span class="user_select">{{ $user->under_name_kana }})</span>
      </div>
      <div>
        @if($user->sex == 1)
        <span>性別 : </span><span class="user_select">男</span>
        @elseif($user->sex == 2)
        <span>性別 : </span><span class="user_select">女</span>
        @else
        <span>性別 : </span><span class="user_select">その他</span>
        @endif
      </div>
      <div>
        <span>生年月日 : </span><span class="user_select">{{ $user->birth_day }}</span>
      </div>
      <div>
        @if($user->role == 1)
        <span>権限 : </span><span class="user_select">教師(国語)</span>
        @elseif($user->role == 2)
        <span>権限 : </span><span class="user_select">教師(数学)</span>
        @elseif($user->role == 3)
        <span>権限 : </span><span class="user_select">講師(英語)</span>
        @else
        <span>権限 : </span><span class="user_select">生徒</span>
        @endif
      </div>
      <div>
        @if($user->role == 4)
        <span>選択科目 : </span>
        @if ($user->subjects)
      @foreach ($user->subjects as $subject)
        <span>{{ $subject->subject }}</span>
      @endforeach
    @endif
    @endif

      </div>
      </div>
    </div>
    @endforeach
  </div>



  <!-- ここから検索機能 -->
  <div class="search_area w-25">
    <div class="search_sidebar">
      <div>
        <label class="search_label search_label_top">検索</label>
        <input type="text" class="free_word engineer" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
      </div>
      <div>
        <label class="search_label">カテゴリ</label>
        <select class="engineer" form="userSearchRequest" name="category">
          <option  value="name">名前</option>
          <option value="id">社員ID</option>
        </select>
      </div>
      <div>
        <label class="search_label">並び替え</label>
        <select class="engineer" name="updown" form="userSearchRequest">
          <option value="ASC">昇順</option>
          <option value="DESC">降順</option>
        </select>
      </div>
      <div class="">
        <div class="search_conditions search-toggle" id="searchToggle">
  <span class="search_more_title search_label">検索条件の追加</span>
  <span class="arrow"></span>
</div>

        <div class="search_conditions_inner">
          <div>
            <label class="search_label">性別</label>
            <span>男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
            <span>女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
            <span>その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
          </div>
          <div>
            <label class="search_label">権限</label>
            <select name="role" form="userSearchRequest" class="engineer">
              <option selected disabled>----</option>
              <option value="1">教師(国語)</option>
              <option value="2">教師(数学)</option>
              <option value="3">教師(英語)</option>
              <option value="4" class="">生徒</option>
            </select>
          </div>
          <label class="search_label">選択科目</label>
          <div class="subject-container">
             @foreach ($subjects as $subject)
              <div>
              <label>
                {{ $subject->subject }}
                <input type="checkbox" name="subject[]" value="{{ $subject->id }}" form="userSearchRequest">
              </label>
            </div>

            @endforeach
          </div>
          </div>
          </div>
          </div>
          <div class="search-buttons">
        <input type="submit" name="search_btn" value="検索" form="userSearchRequest">
      </div>
      <div class="search-buttons">
        <input type="reset" value="リセット" form="userSearchRequest">
      </div>
        </div>
      </div>

    </div>
    <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
  </div>
</div>
</x-sidebar>

<script>
  document.getElementById('searchToggle').addEventListener('click', function() {
  this.classList.toggle('open');
});

</script>
