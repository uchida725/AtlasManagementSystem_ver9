<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use App\Http\Requests\BulletinBoard\CategoryRequest;
use Auth;

class PostsController extends Controller
{
    public function show(Request $request){
    $posts = Post::with('user', 'postComments','likes')->get();
    $categories = MainCategory::with('subCategories')->get();
    $like = new Like;
    $post_comment = new Post;

    if (!empty($request->keyword)) {
        $keyword = $request->keyword;

        // 🔍 サブカテゴリと完全一致するかチェック
        $matchedSubCategory = SubCategory::where('sub_category', $keyword)->first();

        if ($matchedSubCategory) {
            // ✅ 一致するサブカテゴリがある → そのカテゴリに属する投稿のみ取得
            $posts = Post::with('user', 'postComments')
                ->whereHas('subCategories', function ($query) use ($matchedSubCategory) {
                    $query->where('sub_categories.id', $matchedSubCategory->id);
                })
                ->get();
        } else {
            // ❌ 一致しなければ → 通常のタイトル・本文で部分一致検索
            $posts = Post::with('user', 'postComments')
                ->where('post_title', 'like', '%' . $keyword . '%')
                ->orWhere('post', 'like', '%' . $keyword . '%')
                ->get();
        }

    } else if ($request->sub_category_id) {
        // 🔍 サブカテゴリーID指定で投稿絞り込み
        $category = $request->input('sub_category_id');

        $posts = Post::with('user', 'postComments')
            ->whereHas('subCategories', fn($q) => $q->where('sub_categories.id', $category))
            ->get();

    } else if ($request->like_posts) {
        // 👍 いいねした投稿のみ
        $likes = Auth::user()->likePostId()->get('like_post_id');
        $posts = Post::with('user', 'postComments')
            ->whereIn('id', $likes)
            ->get();

    } else if ($request->my_posts) {
        // 👤 自分の投稿のみ
        $posts = Post::with('user', 'postComments')
            ->where('user_id', Auth::id())
            ->get();
    }

    return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
}


    public function postDetail($post_id){
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::with('subCategories')->get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        // dd($request->all());

        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);
        // サブカテゴリーとの紐づけ！
        if ($request->filled('sub_category_id')) {
                $post->subCategories()->attach($request->sub_category_id);
            }

        return redirect()->route('post.show');
    }

    public function postEdit(Request $request){
        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    public function mainCategoryCreate(CategoryRequest $request){
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    // サブカテゴリ追加
public function subCategoryCreate(CategoryRequest $request){
    SubCategory::create([
        'main_category_id' => $request->main_category_id,
        'sub_category' => $request->sub_category_name
    ]);
    return redirect()->route('post.input');
}



    public function commentCreate(Request $request){
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

        return response()->json();
    }
}
