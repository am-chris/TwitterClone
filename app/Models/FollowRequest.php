<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowRequest extends Model
{
    public function follower()
    {
        return $this->belongsTo('App\User');
    }
}
