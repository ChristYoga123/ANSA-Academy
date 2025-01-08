<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Technology extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_technologies', 'technology_id', 'course_id')->withPivot('id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('technology-thumbnail')
            ->singleFile();
    }
}
