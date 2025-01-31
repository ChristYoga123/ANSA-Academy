<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Lomba extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'waktu_open_registrasi' => 'datetime',
        'waktu_close_registrasi' => 'datetime',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'waktu_selesai_sama_dengan_waktu_mulai' => 'boolean',
    ];

    public function setJudulAttribute($value)
    {
        $this->attributes['judul'] = ucwords($value);
        $this->attributes['slug'] = Str::slug($value);
    }
}
