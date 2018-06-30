<?php

namespace App\Models;

use App\Models\Post\Like;
use App\Models\Post\Share;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function likes()
    {
        return $this->hasMany('App\Model\Post\Like');
    }

    public function shares()
    {
        return $this->hasMany('App\Model\Post\Share');
    }

    public function likedByUser($user_id)
    {
        $user_liked_post = Like::where('post_id', $this->post_id)
            ->where('user_id', $user_id)
            ->first();

        if (is_null($user_liked_post)) {
            return 0;
        }
        
        return 1;
    }

    public function sharedByUser($user_id)
    {
        $user_shared_post = Share::where('post_id', $this->post_id)
            ->where('user_id', $user_id)
            ->first();

        if (is_null($user_shared_post)) {
            return 0;
        }    

        return 1;
    }

    public function post()
    {
        return $this->hasOne('App\Models\Post');
    }
}
