<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    protected $guarded = [];
    protected $table = 'proformas';

    public function interm()
    {
        return $this->belongsTo(Client::class, 'intermediario_id', 'id');
    }
}
