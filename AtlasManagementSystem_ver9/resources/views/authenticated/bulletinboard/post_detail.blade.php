<x-sidebar>
<div class="vh-100 d-flex">
  <div class="w-50 mt-5">
    <div class="m-3 detail_container">
      <div class="p-3">
        <div class="detail_inner_head">
          <div>
          </div>
          @auth
            @if (Auth::id() === $post->user_id)
              <div>
                <!-- ✅ これが必要！JSにURLのひな形を渡す -->
                <input type="hidden" id="delete-url-base" value="{{ route('post.delete', ['id' => 'POST_ID']) }}">

                <span class="edit-modal-open" post_title="{{ $post->post_title }}" post_body="{{ $post->post }}" post_id="{{ $post->id }}">編集</span>
                <button type="button" class="btn btn-danger delete-modal-open" data-post-id="{{ $post->id }}">
                  削除
                </button>
              </div>
            @endif
          @endauth

        </div>

        <div class="contributor d-flex">
          <p>
            <span>{{ $post->user->over_name }}</span>
            <span>{{ $post->user->under_name }}</span>
            さん
          </p>
          <span class="ml-5">{{ $post->created_at }}</span>
        </div>
        <div class="detsail_post_title">{{ $post->post_title }}</div>
        <div class="mt-3 detsail_post">{{ $post->post }}</div>
      </div>
      <div class="p-3">
        <div class="comment_container">
          <span class="">コメント</span>
          @foreach($post->postComments as $comment)
          <div class="comment_area border-top">
            <p>
              <span>{{ $comment->commentUser($comment->user_id)->over_name }}</span>
              <span>{{ $comment->commentUser($comment->user_id)->under_name }}</span>さん
            </p>
            <p>{{ $comment->comment }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <div class="w-50 p-3">
    <div class="comment_container border m-5">
      <div class="comment_area p-3">
        @if ($errors->has('comment'))
            <div class="text-danger">
                {{ $errors->first('comment') }}
            </div>
        @endif
        <p class="m-0">コメントする</p>
        <textarea class="w-100" name="comment" form="commentRequest"></textarea>
        <input type="hidden" name="post_id" form="commentRequest" value="{{ $post->id }}">
        <input type="submit" class="btn btn-primary" form="commentRequest" value="投稿">
        <form action="{{ route('comment.create') }}" method="post" id="commentRequest">{{ csrf_field() }}</form>
      </div>
    </div>
  </div>
</div>
<div class="modal js-modal">
  <div class="modal__bg js-modal-close"></div>
  <div class="modal__content">
    <form action="{{ route('post.edit') }}" method="post">
      <div class="w-100">
        <div class="modal-inner-title w-50 m-auto">
          <input type="text" name="post_title" placeholder="タイトル" class="w-100">
        </div>
        <div class="modal-inner-body w-50 m-auto pt-3 pb-3">
          <textarea placeholder="投稿内容" name="post_body" class="w-100"></textarea>
        </div>
        <div class="w-50 m-auto edit-modal-btn d-flex">
          <a class="js-modal-close btn btn-danger d-inline-block" href="">閉じる</a>
          <input type="hidden" class="edit-modal-hidden" name="post_id" value="">
          <input type="submit" class="btn btn-primary d-block" value="編集">
        </div>
      </div>
      {{ csrf_field() }}
    </form>
  </div>
</div>
<div class="modal js-delete-modal">
  <div class="modal__bg js-delete-modal-close"></div>
  <div class="modal__content">
    <p class="mb-3">この投稿を本当に削除しますか？</p>
    <form id="deleteForm" method="GET">
      <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-secondary js-delete-modal-close">キャンセル</button>
        <button type="submit" class="btn btn-danger ml-2">削除する</button>
      </div>
    </form>
  </div>
</div>

<script>
  // モーダル開く
  document.querySelectorAll('.delete-modal-open').forEach(button => {
  button.addEventListener('click', function () {
    const postId = this.getAttribute('data-post-id');
    const baseUrl = document.getElementById('delete-url-base').value;
    const finalUrl = baseUrl.replace('POST_ID', postId);

    const form = document.getElementById('deleteForm');
    form.setAttribute('action', finalUrl);
    document.querySelector('.js-delete-modal').classList.add('is-show');
  });
});


  // モーダル閉じる
  document.querySelectorAll('.js-delete-modal-close').forEach(button => {
    button.addEventListener('click', function () {
      document.querySelector('.js-delete-modal').classList.remove('is-show');
    });
  });
</script>

</x-sidebar>
