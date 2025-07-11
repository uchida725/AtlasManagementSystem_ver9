<x-sidebar>
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    <p class="w-75 m-auto">投稿一覧</p>
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area d-flex">
        <div class="d-flex post_status">
          <div class="mr-5">
            <i class="fa fa-comment"></i>
            <!-- ↓ここにカウント数を追加 -->
            <span class="">{{ $post->postComments->count() }}</span>
          </div>
          <div>
            @if(Auth::user()->is_Like($post->id))
            <p class="m-0">
              <i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
              <span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span>
            </p>
            @else
            <p class="m-0">
              <i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i>
              <span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span>
            </p>
            @endif

          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <!-- ここから右側の絞り込み検索部分 -->
  <div class="other_area border w-25">
    <div class="border m-4">
      <div class=""><a href="{{ route('post.input') }}">投稿</a></div>
      <div class="button_search_area">
        <form action="{{ route('post.show') }}" method="get" id="postSearchRequest">
          <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
        <input type="submit" value="検索" form="postSearchRequest">

        </form>

      </div>
      <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
      <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest">
      <!-- カテゴリの検索機能 -->
      <ul>
        @foreach($categories as $category)
        <li class="main_categories" category_id="{{ $category->id }}"><span>{{ $category->main_category }}<span></li>
        <!-- {{-- サブカテゴリー一覧（クリックで絞り込み） --}} -->
    @foreach($category->subCategories as $sub)
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest">
    <input type="hidden" name="sub_category_id" value="{{ $sub->id }}">
    <button type="submit" class="category_btn">{{ $sub->sub_category }}</button>
  </form>
@endforeach
        @endforeach
      </ul>
    </div>
  </div>
</div>
</x-sidebar>
