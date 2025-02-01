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

    public function setJudulAttribute($value)
    {
        $this->attributes['judul'] = ucwords($value);
        $this->attributes['slug'] = Str::slug($value);
    }

    public function mentees()
    {
        return $this->belongsToMany(User::class, 'program_mentees', 'program_id', 'mentee_id');
    }

    public function mentors()
    {
        return $this->belongsToMany(User::class, 'program_mentors', 'program_id', 'mentor_id');
    }

    public function mentoringPakets()
    {
        return $this->hasMany(MentoringPaket::class, 'mentoring_id');
    }

    public function kelasAnsaDetail()
    {
        return $this->hasOne(KelasAnsaDetail::class, 'kelas_ansa_id');
    }

    public function kelasAnsaPakets()
    {
        return $this->hasMany(KelasAnsaPaket::class, 'kelas_ansa_id');
    }

    public function proofreadingPakets()
    {
        return $this->hasMany(ProofreadingPaket::class, 'proofreading_id');
    }
}
