<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramMentee extends Model
{
    protected $guarded = ['id'];

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
}
