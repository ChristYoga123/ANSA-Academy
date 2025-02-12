<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramKategori extends Model
{
    protected $guarded = ['id'];

    public function programs()
    {
        return $this->hasMany(Program::class, 'program_kategori_id');
    }
}
