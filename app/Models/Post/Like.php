<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'post_likes';

    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
