<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokerMentorBidangKualifikasi extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    public function lokerMentorBidang()
    {
        return $this->belongsTo(LokerMentorBidang::class);
    }
}
