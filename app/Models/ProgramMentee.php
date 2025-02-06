<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProgramMentee extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $guarded = ['id'];

    protected $with = [
        'program'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function paketable()
    {
        return $this->morphTo('paketable');
    }

    public function transaksis()
    {
        return $this->morphMany(Transaksi::class, 'transaksiable');
    }

    public function proofreadingMenteeSubmission()
    {
        return $this->hasOne(ProofreadingMenteeSubmission::class, 'proofreading_mentee_id');
    }

    public function mentoringMenteeJadwal()
    {
        return $this->hasMany(MentoringJadwal::class, 'mentoring_mentee_id');
    }
}
