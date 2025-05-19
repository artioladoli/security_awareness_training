<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected static $unguarded = true;
    public $incrementing = false;
    protected $primaryKey = ['attempt_id','option_id'];
    public $timestamps = false;

    public function attempt()  { return $this->belongsTo(Attempt::class); }
    public function option()   { return $this->belongsTo(Option::class); }
}
