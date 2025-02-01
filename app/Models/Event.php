<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'waktu_open_registrasi' => 'datetime',
        'waktu_close_registrasi' => 'datetime',
    ];

    public function setJudulAttribute($value)
    {
        $this->attributes['judul'] = ucwords($value);
        $this->attributes['slug'] = Str::slug($value);
    }

    public function mentors()
    {
        return $this->belongsToMany(User::class, 'event_mentors', 'event_id', 'mentor_id');
    }

    public function eventJadwals()
    {
        return $this->hasMany(EventJadwal::class);
    }

    public function transaksi()
    {
        return $this->morphMany(Transaksi::class, 'transaksiable');
    }
}
