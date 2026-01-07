<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

    public function strutture()
    {
        return $this->belongsTo(Strutture::class);
    }

    public function proformas()
    {
        return $this->hasMany(Proforma::class);
    }

    public function intermediari()
    {
        return $this->hasMany(Proforma::class, 'intermediario_id');
    }
}
