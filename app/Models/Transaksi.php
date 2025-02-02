<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $guarded = ['id'];

    public function transaksiable()
    {
        return $this->morphTo();
    }

    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
}
