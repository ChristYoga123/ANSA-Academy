<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProofreadingMenteeSubmission extends Model
{
    protected $guarded = ['id'];

    public function programMentee()
    {
        return $this->belongsTo(ProgramMentee::class, 'proofreading_mentee_id');
    }
}
