<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    protected static $unguarded = true;

    public function user()      { return $this->belongsTo(User::class); }
    public function attempts()  { return $this->hasMany(Attempt::class); }
}
