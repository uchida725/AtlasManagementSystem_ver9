<x-sidebar>
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p class="post-name"><span >{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p><a href="{{ route('post.detail', ['id' => $post->id]) }}" class="no-link-style">{{ $post->post_title }}</a></p>


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
              <i class="far fa-heart like_btn" post_id="{{ $post->id }}"></i>
              <span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span>
            </p>
            @endif

          </div>
        </div>
      </div>
      @if($post->subCategories->isNotEmpty())
        <div class="post_category_area">
          @foreach($post->subCategories as $sub)
            <span class="category_badge">{{ $sub->sub_category }}</span>
          @endforeach
        </div>
      @endif
    </div>
    @endforeach
  </div>
  <!-- ここから右側の絞り込み検索部分 -->
<div class="other_area w-30">
  <div class="m-4">
    <div class="mb-3"><a href="{{ route('post.input') }}" class="custom-main-btn">投稿</a></div>

    <div class="button_search_area mb-3">
      <form action="{{ route('post.show') }}" method="get" id="postSearchRequest" class="d-flex">
        <input type="text" placeholder="キーワードを検索" name="keyword" class="form-control mr-2">
        <input type="submit" value="検索" class="custom-main-search-btn">
      </form>
    </div>

    <div class="d-flex justify-content-between mb-3">
      <input type="submit" name="like_posts" class="category_btn like-btn" value="いいねした投稿" form="postSearchRequest">
      <input type="submit" name="my_posts" class="category_btn my-btn" value="自分の投稿" form="postSearchRequest">
    </div>

    <p class="mb-1">カテゴリー検索</p>
    <div class="accordion-area">
      @foreach($categories as $category)
        <div class="accordion-category mb-2">
          <div class="accordion-header" onclick="toggleAccordion(this)">
            <span>{{ $category->main_category }}</span>
            <span class="arrow"></span>
          </div>
          <div class="accordion-body">
            @foreach($category->subCategories as $sub)
              <form action="{{ route('post.show') }}" method="get" class="mb-1">
                <input type="hidden" name="sub_category_id" value="{{ $sub->id }}">
                <button type="submit" class="sub-category-btn">{{ $sub->sub_category }}</button>
              </form>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

</x-sidebar>

<script>
  function toggleAccordion(header) {
    const body = header.nextElementSibling;
    const arrow = header.querySelector('.arrow');

    body.classList.toggle('open');
    arrow.classList.toggle('open');
  }
</script>
