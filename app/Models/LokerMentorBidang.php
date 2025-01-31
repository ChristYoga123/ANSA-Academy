<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokerMentorBidang extends Model
{
    protected $guarded = ['id'];

    public function lokerMentorBidangKualifikasi()
    {
        return $this->hasMany(LokerMentorBidangKualifikasi::class);
    }
}
