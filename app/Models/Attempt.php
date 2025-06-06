<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected static $unguarded = true;

    public function session()   { return $this->belongsTo(TrainingSession::class); }
    public function user()      { return $this->belongsTo(User::class); }
    public function topic()    { return $this->belongsTo(Topic::class); }
    public function answers()   { return $this->hasMany(Answer::class); }
}
