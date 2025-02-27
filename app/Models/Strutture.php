<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class Strutture extends Model
{
    protected $table = "struttures";
    protected $guarded = [];

    public function scopeFiliali(Builder $builder)
    {
        return $builder->where('tipo', 'Corporate');
    }

    public function scopeRecapiti(Builder $builder)
    {
        return $builder->where('tipo', 'Recapito');
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function caps()
    {
        return $this->hasMany(Strutturecap::class);
    }
}
