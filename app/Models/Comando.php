<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comando extends Model
{
    use HasFactory;

    protected $table = 'Comandi';

    protected $fillable = [
        'Nome',
        'Codice',
        'codTipoDisp',
        'codAutomazione',
        'isAutomatico',
        'Descrizione',
        'isManuale'
    ];

    public $timestamps = false;
    
}
