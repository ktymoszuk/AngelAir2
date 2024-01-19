<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struttura extends Model
{
    use HasFactory;

    protected $table = "Strutture";

    protected $fillable = ["Nome", "Indirizzo", "Referente", "Latitudine", "Longitudine", "Identificativo"];

    public $timestamps = false;

    public function automazione()
    {
        return $this->hasOne(Automazione::class, 'id', 'codAutomazione');
    }
    public function dispositivi()
    {
        return $this->hasMany(Dispositivo::class, 'codStruttura', 'id');
    }
}
