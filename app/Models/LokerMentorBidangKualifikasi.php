<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokerMentorBidangKualifikasi extends Model
{
    protected $guarded = ['id'];

    public function lokerMentorBidang()
    {
        return $this->belongsTo(LokerMentorBidang::class);
    }
}
