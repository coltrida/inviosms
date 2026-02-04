<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disponibilita extends Model
{
    protected $guarded = [];
    protected $table = 'disponibilitas';

    public function struttura()
    {
        return $this->belongsTo(Strutture::class, 'strutture_id', 'id');
    }
}
