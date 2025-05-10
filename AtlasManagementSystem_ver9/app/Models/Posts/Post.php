<?php

namespace App\Models\Posts;

use App\Models\Categories\SubCategory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories(){
        // リレーションの定義
        return $this->belongsToMany(SubCategory::class, 'post_sub_categories', 'post_id', 'sub_category_id');

    }

    // コメント数
    public function commentCounts($post_id){
        return Post::with('postComments')->find($post_id)->postComments();
    }

    // いいねカウント数表示のためのリレーション
    public function likes()
    {
        return $this->hasMany(Like::class, 'like_post_id');
    }

    // コメントカウント数表示のためのリレーション
    public function post_Comments()
    {
        return $this->hasMany(PostComment::class);
    }

}
