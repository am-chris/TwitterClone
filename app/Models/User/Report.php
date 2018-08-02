<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['reported_id', 'reporter_id', 'reason', 'type', 'description', 'action', 'duration'];

    public function reporter()
    {
        return $this->belongsTo('App\User', 'reporter_id');
    }

    public function reported()
    {
        return $this->belongsTo('App\User', 'reported_id');
    }
}
