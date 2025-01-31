<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasAnsaPaket extends Model
{
    protected $guarded = ['id'];

    public function kelasAnsa()
    {
        return $this->belongsTo(Program::class, 'kelas_ansa_id');
    }
}
