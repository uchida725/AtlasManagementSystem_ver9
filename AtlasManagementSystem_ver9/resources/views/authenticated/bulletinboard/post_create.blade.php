<x-sidebar>

  <!-- 投稿作成フォーム -->
  <form action="{{ route('post.create') }}" method="post" id="postCreate">
    @csrf
    <div class="post_create_container d-flex">
      <div class="post_create_area border w-50 m-5 p-5">

        <!-- カテゴリー選択 -->
        <p class="mb-0">カテゴリー</p>
        <select class="w-100" name="sub_category_id">
          @foreach($main_categories as $main_category)
            <optgroup label="{{ $main_category->main_category }}">
              @foreach($main_category->subCategories as $sub)
                <option value="{{ $sub->id }}">{{ $sub->sub_category }}</option>
              @endforeach
            </optgroup>
          @endforeach
        </select>

        <!-- タイトル -->
        <div class="mt-3">
          @if($errors->first('post_title'))
            <span class="error_message">{{ $errors->first('post_title') }}</span>
          @endif
          <p class="mb-0">タイトル</p>
          <input type="text" class="w-100" name="post_title" value="{{ old('post_title') }}">
        </div>

        <!-- 投稿内容 -->
        <div class="mt-3">
          @if($errors->first('post_body'))
            <span class="error_message">{{ $errors->first('post_body') }}</span>
          @endif
          <p class="mb-0">投稿内容</p>
          <textarea class="w-100" name="post_body">{{ old('post_body') }}</textarea>
        </div>

        <div class="mt-3 text-right">
          <input type="submit" class="btn btn-primary" value="投稿">
        </div>
      </div>
    </div>
  </form>


  <!-- 管理者用 カテゴリー追加フォーム -->
  @can('admin')
    <div class="w-25 ml-auto mr-auto">
      <div class="category_area mt-5 p-5">

        <!-- メインカテゴリー -->
        @if ($errors->has('main_category_name'))
          <div class="text-danger">{{ $errors->first('main_category_name') }}</div>
        @endif
        <form action="{{ route('main.category.create') }}" method="post">
          @csrf
          <p class="m-0">メインカテゴリー</p>
          <input type="text" class="w-100" name="main_category_name">
          <input type="submit" value="追加" class="w-100 btn btn-primary p-0">
        </form>

        <!-- サブカテゴリー -->
        @if ($errors->has('sub_category_name'))
          <div class="text-danger">{{ $errors->first('sub_category_name') }}</div>
        @endif
        <form action="{{ route('sub.category.create') }}" method="POST">
          @csrf
          <p class="m-0">サブカテゴリー</p>
          <select name="main_category_id" class="w-100">
            <option value="">---</option>
            @foreach($main_categories as $main_category)
              <option value="{{ $main_category->id }}">{{ $main_category->main_category }}</option>
            @endforeach
          </select>
          <input type="text" class="w-100" name="sub_category_name">
          <input type="submit" value="追加" class="btn btn-primary w-100 mt-2 p-0">
        </form>

      </div>
    </div>
  @endcan

</x-sidebar>
