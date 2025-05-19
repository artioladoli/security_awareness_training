<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected static $unguarded = true;

    public function topic()    { return $this->belongsTo(Topic::class); }
    public function options()   { return $this->hasMany(Option::class); }
}
