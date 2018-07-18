<?php

namespace App;

use Auth;
use App\Models\Follow;
use App\Models\FollowRequest;
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
        return $this->hasMany('App\Models\Post\Like');
    }

    public function shares()
    {
        return $this->hasMany('App\Models\Post\Share');
    }

    public function blocked()
    {
       return $this->belongsToMany(self::class, 'blocked_users', 'blocker_id', 'blocked_id');
    }

    public function block($user_id)
    {
       return $this->blocked()->attach($user_id);
    }

    public function follows()
    {
       return $this->belongsToMany(self::class, 'follows', 'follower_id', 'followed_id');
    }

    public function follow($user_id)
    {
       return $this->follows()->attach($user_id);
    }

    public function followingUser($user_id)
    {
        $query = Follow::where('followed_id', $user_id)
            ->where('follower_id', Auth::id())
            ->first();

        if (!is_null($query)) {
            return true;
        }

        return false;
    }

    public function followRequested($userId)
    {
        $query = FollowRequest::where('followed_id', $userId)
            ->where('follower_id', Auth::id())
            ->first();

        if (!is_null($query)) {
            return true;
        } 

        return false;
    }

    public function followers()
    {
       return $this->belongsToMany(self::class, 'follows', 'followed_id', 'follower_id');
    }
}
