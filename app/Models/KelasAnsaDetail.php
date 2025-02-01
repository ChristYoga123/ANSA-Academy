<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasAnsaDetail extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'waktu_open_registrasi' => 'datetime', 
        'waktu_close_registrasi' => 'datetime', 
        'waktu_mulai' => 'date', 
        'waktu_selesaii' => 'date', 
    ];

    public function kelasAnsa()
    {
        return $this->belongsTo(Program::class, 'kelas_ansa_id');
    }
}
