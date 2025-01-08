<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseChapterLesson extends Model
{
    protected $guarded = ['id'];

    public function courseChapter()
    {
        return $this->belongsTo(CourseChapter::class);
    }
}
