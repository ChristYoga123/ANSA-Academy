<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $guarded = ['id'];

    public function promoable()
    {
        return $this->morphTo();
    }
}
