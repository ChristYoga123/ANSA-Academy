<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Program extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'jadwal_kegiatan' => 'array'
    ];

    public function setJudulProgramAttribute($value)
    {
        $this->attributes['judul_program'] = ucwords($value);
        $this->attributes['slug'] = Str::slug($value);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('program-thumbnail')
            ->singleFile();
        $this->addMediaCollection('program-gallery');
    }
}
