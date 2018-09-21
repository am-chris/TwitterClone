<?php

namespace App;

use Auth;
use Redis;
use App\Models\Follow;
use App\Models\FollowRequest;
use App\Models\User\Report;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'password', 'photo_url', 'cover_photo_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email', 'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function likes()
    {
        return Redis::zrange('post_likes:' . $this->id, 0, -1);
    }

    public function shares()
    {
        return Redis::zrange('post_shares:' . $this->id, 0, -1);
    }

    public function block($user)
    {
        Redis::zadd('blockers:' . $user->id, time(), $this->id);
        Redis::zadd('blocking:' . $this->id, time(), $user->id);
    }

    public function unblock($user)
    {
        Redis::zrem('blockers:' . $user->id, time(), $this->id);
        Redis::zrem('blocking:' . $this->id, time(), $user->id);
    }

    public function follow($user)
    {
        if ($user->private) {
            Redis::zadd('follow_requests:' . $user->id, time(), $this->id);
        } else {
            Redis::zadd('followers:' . $user->id, time(), $this->id);
            Redis::zadd('following:' . $this->id, time(), $user->id);
        }
    }

    public function unfollow($user)
    {
        Redis::zrem('followers:' . $user->id, time(), $this->id);
        Redis::zrem('following:' . $this->id, time(), $user->id);
    }

    public function followRequested($user)
    {
        return Redis::zscore('follow_requests:' . $user->id, $this->id) ? true : false;
    }

    public function following()
    {
        return Redis::zrange('following:' . $this->id, 0, -1);
    }

    public function followingUser($user)
    {
        return Redis::zscore('following:' . $this->id, $user->id) ? true : false;
    }

    public function followers()
    {
        return Redis::zrange('followers:' . $this->id, 0, -1);
    }

    public function followRequests()
    {
        return Redis::zrange('follow_requests:' . $this->id, 0, -1);
    }

    public function approveFollowRequest($user)
    {
        Redis::zrem('follow_requests:' . $this->id, time(), $user->id);
        
        Redis::zadd('followers:' . $this->id, time(), $user->id);
        Redis::zadd('following:' . $user->id, time(), $this->id);
    }

    public function denyFollowRequest($user)
    {
        Redis::zrem('follow_requests:' . $this->id, time(), $user->id);
    }

    public function blocking()
    {
        return Redis::zrange('blocking:' . $this->id, 0, -1);
    }

    public function blockingUser($user)
    {
        return Redis::zscore('blocking:' . $this->id, $user->id) ? true : false;
    }

    public function likePost($post)
    {
        Redis::zadd('post_likes:' . $post->id, time(), $this->id);
    }

    public function unlikePost($post)
    {
        Redis::zrem('post_likes:' . $post->id, time(), $this->id);
    }

    public function sharePost($post)
    {
        Redis::zadd('post_shares:' . $post->id, time(), $this->id);
    }

    public function unsharePost($post)
    {
        Redis::zrem('post_shares:' . $post->id, time(), $this->id);
    }
}
