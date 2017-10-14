<?php

namespace App;

use Auth;
use App\Models\Follow;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email', 'password', 'remember_token', 'created_at',
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

    public function follows()
    {
       return $this->belongsToMany(self::class, 'follows', 'follower_id', 'followed_id');
    }

    public function followers()
    {
       return $this->belongsToMany(self::class, 'follows', 'followed_id', 'follower_id');
    }

    public function follow($userIdToFollow)
    {
       return $this->follows()->attach($userIdToFollow);
    }

    public function followingUser($user_id)
    {
        $query = Follow::where('followed_id', $user_id)
            ->where('follower_id', Auth::id())
            ->first();

        if (count($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function photo($user_id)
    {
        $user = User::findOrFail($user_id);

        if (!is_null($user->photo_url)) {
            return $user->photo_url;
        } else {
            return url('/img/profile/mysteryman.png');
        }
    }
}
