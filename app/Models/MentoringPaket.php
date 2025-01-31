<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentoringPaket extends Model
{
    protected $guarded = ['id'];

    public function mentoring()
    {
        return $this->belongsTo(Program::class, 'mentoring_id');
    }
}
