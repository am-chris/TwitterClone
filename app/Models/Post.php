<?php

namespace App\Models;

use App\Models\Post\Like;
use App\Models\Post\Share;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $appends = ['share_count', 'like_count', 'shared_by_user', 'liked_by_user'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function likes()
    {
        return Redis::zrange('post_likes:' . $this->id, 0, -1);
    }

    public function getLikeCountAttribute()
    {
        return count(Redis::zrange('post_likes:' . $this->id, 0, -1));
    }

    public function shares()
    {
        return Redis::zrange('post_shares:' . $this->id, 0, -1);
    }

    public function getShareCountAttribute()
    {
        return count(Redis::zrange('post_shares:' . $this->id, 0, -1));
    }

    public function getSharedByUserAttribute()
    {
        return Redis::zscore('post_shares:' . $this->id, Auth::id()) ? true : false;
    }

    public function getLikedByUserAttribute()
    {
        return Redis::zscore('post_likes:' . $this->id, Auth::id()) ? true : false;
    }

    public function sharedByUser($user)
    {
        return Redis::zscore('post_shares:' . $this->id, $user->id) ? true : false;
    }

    public function likedByUser($user)
    {
        return Redis::zscore('post_likes:' . $this->id, $user->id) ? true : false;
    }

    public function postsSharedByUser($userId)
    {
        return Redis::zrange('post_shares:' . $this->id, 0, -1);
    }

    public function post()
    {
        return $this->hasOne('App\Models\Post');
    }

    public static function timeline()
    {
        return $this->postsSharedByUser();
    }
}
