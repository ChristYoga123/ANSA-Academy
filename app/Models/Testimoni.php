<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimoni extends Model
{
    protected $guarded = ['id'];

    public function testimoniable()
    {
        return $this->morphTo();
    }

    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
}
