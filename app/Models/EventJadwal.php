<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventJadwal extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'waktu' => 'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
