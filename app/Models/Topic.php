<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected static $unguarded = true;

    public function roles()     { return $this->belongsToMany(Role::class); }
    public function questions() { return $this->hasMany(Question::class); }
    public function attempts()  { return $this->hasMany(Attempt::class); }
}
