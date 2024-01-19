<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Automazione extends Model
{
    use HasFactory;

    protected $table = "Automazioni";

    public $timestamps = false;

    public function scenari()
    {
        return $this->hasMany(Scenario::class, 'codAutomazione', 'id');
    }

    public function strutture()
    {
        return $this->hasMany(Struttura::class, 'codAutomazione', 'id');
    }

    public function apptag() {
        return $this->hasManyThrough(Dispositivo::class, Struttura::class, 'codAutomazione', 'codStruttura');
    }
}
