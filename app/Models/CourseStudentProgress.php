<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseStudentProgress extends Model
{
    protected $guarded = ['id'];

    public function courseStudent()
    {
        return $this->belongsTo(CourseStudent::class);
    }

    public function courseChapterLesson()
    {
        return $this->belongsTo(CourseChapterLesson::class);
    }
}
