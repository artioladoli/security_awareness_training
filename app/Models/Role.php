<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected static $unguarded = true;

    public function users()     { return $this->hasMany(User::class); }
    public function topics()   { return $this->belongsToMany(Topic::class); }
}
