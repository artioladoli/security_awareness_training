<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected static $unguarded = true;

    public function question()  { return $this->belongsTo(Question::class); }
    public function answers()   { return $this->hasMany(Answer::class); }
}
