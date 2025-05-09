<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'like_user_id',
        'like_post_id'
    ];

    public function likeCounts($post_id){
        return $this->where('like_post_id', $post_id)->get()->count();
    }

    // 投稿とのリレーション（多対1）
    public function post()
    {
        return $this->belongsTo(Post::class, 'like_post_id');
    }

    // ユーザーとのリレーション（多対1）
    public function user()
    {
        return $this->belongsTo(User::class, 'like_user_id');
    }
}
