<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentoringJadwal extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function mentoringMentee()
    {
        return $this->belongsTo(ProgramMentee::class, 'mentoring_mentee_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
