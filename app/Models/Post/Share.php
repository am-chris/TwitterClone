<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
